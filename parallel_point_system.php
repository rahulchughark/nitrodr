<?php include('includes/header.php');
admin_page();
ini_set('max_execution_time', '0');

if ($_GET['d_from'] && $_GET['d_to']) {
    $d_from = $_GET['d_from'];
    $d_to = $_GET['d_to'];
} else {
    $d_from = date('Y-m-d');
    $d_to = date('Y-m-d');
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
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >VAR Parallel Point System</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Parallel Point System</h4>
                                </div>
                            </div>
                            <br>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form class="form-horizontal" role="form" method="get">

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

                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th rowspan="2">Rank</th>
                                            <th rowspan="2">User Name</th>
                                            <th rowspan="2">Partner Name</th>
                                            <th class="text-center" colspan="1">Points</th>
                                            <th class="text-center" colspan="">Points</th>
                                            <th class="text-center" colspan="1">Points</th>
                                            <th class="text-center" colspan="1">Points</th>
                                            <th rowspan="2" class="text-center">Total Points</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Lead Qualified</th>
                                            <th class="text-center">Product Demo</th>
                                            <th class="text-center">Product POC (Evaluation)</th>
                                            <th class="text-center">OEM Billing</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                       $query= userDataParallel('user_points');
                                       //db_query("select up.user_id,u.name as user,p.name as partner,p.id from user_points as up left join users as u on up.user_id=u.id left join partners as p on up.stage_id=p.id where p.status='Active' and FIND_IN_SET(4,p.product_id) group by up.user_id order by up.id desc");

                                        while ($data = db_fetch_array($query)) {

                                            $approved = approvedDataParallel($data['id'],$d_from,$d_to);
                                            //getSingleresult("SELECT IFNULL(sum(u.point),0) from user_points as u left join orders as o on u.lead_id=o.id WHERE u.stage_id=" . $data['id'] . " and date(o.approval_time) >='" . $d_from . "' and date(o.approval_time)<='" . $d_to . "' and u.stage_name='Approved' and o.license_type='Commercial'");

                                            $product_demo = getSingleresult("SELECT IFNULL(sum(u.point),0) from user_points as u left join orders as o on u.lead_id=o.id WHERE u.stage_id=".$data['id']." and MONTH(o.partner_close_date)='".($_GET['d_from']?date("m",strtotime($d_from)):date("n", strtotime('F')))."' and YEAR(o.partner_close_date)='".($_GET['d_from']?date('Y',strtotime($d_from)):date('Y'))."' and u.stage_name='Product Demo' and o.license_type='Commercial'");

                                            $product_poc = getSingleresult("SELECT IFNULL(sum(u.point),0) from user_points as u left join orders as o on u.lead_id=o.id WHERE u.stage_id=" . $data['id'] . " and MONTH(o.partner_close_date)='".($_GET['d_from']?date("m",strtotime($d_from)):date("n", strtotime('F')))."' and YEAR(o.partner_close_date)='".($_GET['d_from']?date('Y',strtotime($d_from)):date('Y'))."' and u.stage_name='Product POC (Evaluation)' and o.license_type='Commercial'");

                                            $oem_billing = getSingleresult("SELECT IFNULL(sum(u.point),0) from user_points as u left join orders as o on u.lead_id=o.id WHERE u.stage_id=" . $data['id'] . " and MONTH(o.partner_close_date)='".($_GET['d_from']?date("m",strtotime($d_from)):date("n", strtotime('F')))."' and YEAR(o.partner_close_date)='".($_GET['d_from']?date('Y',strtotime($d_from)):date('Y'))."' and u.stage_name='OEM Billing' and o.license_type='Commercial'");

                                            $points = $approved + $product_demo + $product_poc + $oem_billing;

                                        ?>

                                            <tr>
                                                <td><strong><?= $i ?></strong></td>
                                                <td><?= $data['user'] ?> </td>
                                                <td><?= $data['partner'] ?> </td>
                                                <td class="text-center"><?= ($approved ? $approved : 0) ?></td>
                                                <td class="text-center"><?= $product_demo ?></td>
                                                <td class="text-center"><?= ($product_poc ? $product_poc : 0) ?></td>
                                                <td class="text-center"><?= $oem_billing ?></td>

                                                <td class="text-center"><strong><span class="text-grey"><?= ($points ? $points : '0') ?></strong></span></td>
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
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <div id="myModal" class="modal fade" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
        <script>
            $('#example23').DataTable({
                "displayLength": 500,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                columnDefs: [{
                    orderable: false
                }],
                lengthMenu: [
                    [500, 1000],
                    ['500', '1000']
                ],
            });


            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'parallel_point_system.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            });
        </script>