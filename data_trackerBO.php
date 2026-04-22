<?php include('includes/header.php');

$_GET['d_from'] = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to'] = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');
if ($_GET['d_from'] && $_GET['d_to']) {
    $dat1 = " and date(o.created_date)>='" . $_GET['d_from'] . "'";
    $dat2 = " and date(o.created_date)<='" . $_GET['d_to'] . "'";
} else {
    $dat1 = " and MONTH(o.created_date)='" . date('m') . "'";
    $dat2 = " and YEAR(o.created_date)='" . date('Y') . "'";
}

if($_GET['users']){
    $contd .= " and id in (". stripslashes(implode(',',$_GET['users'])) .")";
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

                                    <small class="text-muted">Home >CDGS Data Submission Tracker</small>
                                    <h4 class="font-size-14 m-0 mt-1">CDGS Data Submission Tracker</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search" id="search-form">

                                            <div class="form-group">
                                                <select class="multiselect_users form-control" name="users[]" multiple>

                                                    <?php $res = db_query("select * from users where status='Active' and team_id=" . $_SESSION['team_id'] . " ORDER BY name ASC");
                                                    while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (in_array($row['id'],$users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">

                                <table id="visit_lac" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Member Name</th>
                                            <th>Data Submitted</th>
                                            <th>Data Qualified</th>
                                            <th>Daily Average for Data Submission</th>
                                            <th>Qualified Average %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_z = db_query("select * from users where team_id=" . $_SESSION['team_id'] . " and status='Active' ".$contd." ORDER BY name ASC");

                                        $i = 1;
                                        while ($data_z = db_fetch_array($sql_z)) {
                                            // $totalSubmitted = 0;
                                            // $totalQualified = 0;
                                            // $totalDailyAvg=0;

                                            $data_submitted = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.created_by='" . $data_z['id'] . "'" .$dat1.$dat2. "and o.dvr_flag = 0 and o.iss is NULL ");
                                            $totalSubmitted += $data_submitted;
                                            $data_qualified = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.created_by='" . $data_z['id'] . "' " .$dat1.$dat2. " and o.status='Approved' and o.dvr_flag=0 and o.iss is NULL ");
                                            $totalQualified += $data_qualified;

                                            $daily_avg = $data_submitted / 25;
                                            $totalDailyAvg = $totalSubmitted / 25;

                                            $qualified_avg = @($data_qualified / $data_submitted) * 100;
                                            $qualified_percent = round($qualified_avg);
                                            $totalQualifiedPercent = @($totalQualified / $totalSubmitted) * 100;
                                            //print_r($data_submitted);
                                        ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $data_z['name'] ?></td>
                                                <td><?= $data_submitted ?></td>
                                                <td><?= $data_qualified ?></td>
                                                <td><?= round($daily_avg, 2) ?></td>
                                                <td><?= is_nan($qualified_percent) ? 0 : $qualified_percent.'%' ?></td>
                                            </tr>
                                        <?php $i++;
                                        }  ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <td><?= $totalSubmitted ?></td>
                                            <td><?= $totalQualified ?></td>
                                            <td><?= $totalDailyAvg ?></td>
                                            <td><?= (is_nan($totalQualifiedPercent)||is_infinite($totalQualifiedPercent))? 0 : round($totalQualifiedPercent).'%' ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <?php include('includes/footer.php') ?>
    <script>
        $('#visit_lac').DataTable({

            dom: 'Bfrtip',
            "displayLength": 15,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000, 10000, ],
                ['15', '25', '50', '100', '500', '1000', '10000']
            ],
        });

        $(document).ready(function() {
            $('.multiselect_users').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Users'
            });
        });

        function clear_search() {
            window.location = 'data_trackerBO.php';
        }

        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0

            });

        });
    </script>

    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 310);
            $("#visit_lac").tableHeadFixer();

        });
    </script>