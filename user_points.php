<?php include('includes/header.php');
admin_page();

function getStartAndEndDate($week, $year)
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');

    return $ret;
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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >User Points</small>
                                    <h4 class="font-size-14 m-0 mt-1">User Points</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search">

                                            <div class="form-group">
                                                <select name="week_no" class="form-control">
                                                    <option value="">---Select---</option>

                                                    <?php for ($i = 1; $i <= 52; $i++) {
                                            $weekarray = getStartAndEndDate($i, date('Y'));
                                            //print_r($i);
                                            if ($_GET['week_no']) { ?>

                                                <option <?= (($_GET['week_no'] == $i) ? 'selected' : '')  ?> value="<?= $i ?>">Week <?= $i ?>&nbsp;(<?= date('d-m-Y', strtotime($weekarray['week_start'])) ?> - <?= date('d-m-Y', strtotime($weekarray['week_end'])) ?>)</option>

                                            <?php } else { ?>

                                                <option <?= ((date('W') == $i) ? 'selected' : '')  ?> value="<?= $i ?>">Week <?= $i ?>&nbsp;(<?= date('d-m-Y', strtotime($weekarray['week_start'])) ?> - <?= date('d-m-Y', strtotime($weekarray['week_end'])) ?>)</option>
                                        <?php }
                                        } ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="example_z" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Rank</th>
                                            <th>Name<br><span style="font-size:8px">(Partner Name)</span></th>
                                            <th>New Account</th>
                                            <th>Account Qualified</th>
                                            <th>Customer Connect</th>
                                            <th>Quote</th>
                                            <th> Follow-Up</th>
                                            <th>Commit</th>
                                            <th> EU PO Issued</th>
                                            <th>Booking</th>
                                            <th>Billing<br><span style="font-size:8px">(Points as per No. of Seats)</span></th>
                                            <th> Net Points </th>
                                            <th> Incentive</th>
                                            <th>Total</th>
                                            <th>Rewards<br>Worth (in <i class="mdi mdi-currency-inr"></i>)</th>

                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php
                                    if ($_GET['week_no']) {
                                        $week_check = $_GET['week_no'];
                                    } else {
                                        $week_check = date('W');
                                    }

                                    if ($_SESSION['sales_manager'] != 1) {
                                        $sql_z = db_query("select users.id,users.name,users.team_id,sum(user_points.point) as total,user_points.user_id from users left join user_points on users.id=user_points.user_id left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and user_points.week_number='" . $week_check . "' and YEAR(user_points.created_date)='" . date('Y') . "' and user_points.point!=0 and tp.product_type_id in (1,2) GROUP by user_points.user_id order by total Desc");
                                    } else {
                                        $sql_z = db_query("select users.id,users.name,users.team_id,sum(user_points.point) as total,user_points.user_id from users left join user_points on users.id=user_points.user_id left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id left join partners on users.team_id=partners.id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and partners.id in (" . $_SESSION['access'] . ") and user_points.week_number='" . $week_check . "' and YEAR(user_points.created_date)='" . date('Y') . "' and user_points.point!=0 and tp.product_type_id in (1,2) GROUP by user_points.user_id order by total Desc");
                                       
                                    }
                                    //print_r($sql_z);
                                        $i = 1;

                                        while ($data_z = db_fetch_array($sql_z)) {

                                            $new = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=1000 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $approved = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=1001 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $lc = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=5 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $quote = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=6 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $follow = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=7 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $commit = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=9 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $eupo = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=10 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $booking = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=11 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $billing = getSingleresult("select COALESCE(sum(point),0) from user_points left join tbl_lead_product as tp on user_points.lead_id=tp.lead_id where user_id='" . $data_z['user_id'] . "' and stage_id=12 and tp.product_type_id in (1,2) and week_number=" . $week_check . " and YEAR(user_points.created_date)='" . date('Y') . "'");

                                            $net = $new + $approved + $lc + $quote + $follow + $commit + $eupo + $booking + $billing;

                                            $incentive = getSingleresult("select COALESCE(reward_points,0) from points_rewards where user_id='" . $data_z['user_id'] . "' and week=" . $week_check . " and YEAR(points_rewards.created_date)='" . date('Y') . "'");
                                            $grand_total = $net + $incentive;
                                        ?>

                                            <tr class="text-center">
                                                <td><?= $i ?></td>
                                                <td class="text-left"><?= $data_z['name'] ?><br><span style="font-size:8px">(<?= getSingleresult("select name from partners where id=" . $data_z['team_id']) ?>)</span></td>
                                                <td><?= $new ?></td>
                                                <td><?= $approved ?></td>
                                                <td><?= $lc ?></td>
                                                <td><?= $quote ?></td>
                                                <td><?= $follow ?></td>
                                                <td><?= $commit ?></td>
                                                <td><?= $eupo ?></td>
                                                <td><?= $booking ?></td>
                                                <td><?= $billing; ?></td>
                                                <td><?= $net ?></td>
                                                <td><?= ($incentive ? $incentive : 0) ?></td>
                                                <td><?= $grand_total ?></td>
                                                <td><?= ceil($grand_total / 8) ?>&nbsp;<i class="mdi mdi-currency-inr"></i></td>

                                            </tr>
                                        <?php $i++;
                                        }  ?>
                                    </tbody>
                                </table>

                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <div id="myModal" class="modal fade" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                var table = $('#example_z').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                    ],
                    lengthMenu: [
                        [50, 100, 500, 1000],
                        ['50', '100', '500', '1000']
                    ],
                    "displayLength": 50,

                });

            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example_z").tableHeadFixer();

            });

            //     $(function() {
            //     $('#datepicker-close-date').datepicker({
            //         format: 'yyyy-mm-dd',
            //         //startDate: '-3d',
            //         autoclose: !0

            //     });

            // });

            function clear_search() {
                window.location = 'user_points.php';
            }

            $(function() {
                var startDate;
                var endDate;

                var selectCurrentWeek = function() {
                    window.setTimeout(function() {
                        $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
                    }, 1);
                }

                $('.week-picker').datepicker({
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    onSelect: function(dateText, inst) {
                        var date = $(this).datepicker('getDate');
                        startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
                        endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
                        var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
                        $('#startDate').text($.datepicker.formatDate(dateFormat, startDate, inst.settings));
                        $('#endDate').text($.datepicker.formatDate(dateFormat, endDate, inst.settings));

                        selectCurrentWeek();
                    },
                    beforeShowDay: function(date) {
                        var cssClass = '';
                        if (date >= startDate && date <= endDate)
                            cssClass = 'ui-datepicker-current-day';
                        return [true, cssClass];
                    },
                    onChangeMonthYear: function(year, month, inst) {
                        selectCurrentWeek();
                    }
                });

                $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() {
                    $(this).find('td a').addClass('ui-state-hover');
                });
                $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() {
                    $(this).find('td a').removeClass('ui-state-hover');
                });
            });
        </script>