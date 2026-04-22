<?php include('includes/header.php');
business_owner_page();

if ($_GET['date']) {
    $_GET['date'] = $_GET['date'];
} else {
    $_GET['date'] = date('Y-m-d');
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Reports</small>
                                    <h4 class="font-size-14 m-0 mt-1">Daily Report</h4>
                                </div>
                            </div>




                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <div class="form-group">

                                                <input type="text" value="<?= @$_GET['date'] ?>" class="form-control" id="datepicker_autoclose" name="date" placeholder="Date" />
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive m-t-40">

                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Name</th>
                                            <th>Total DV Entries</th>
                                            <th>Total Log a Call</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                         if($_SESSION['sales_manager'] == 1){
                                            $sql = db_query("select * from users where team_id in(" . $_SESSION['access'] . ") OR id=".$_SESSION['user_id']."");
                                        }else{
                                            $sql = db_query("select * from users where team_id='" . $_SESSION['team_id'] . "' and user_type='USR' ");
                                        }
                                        
                                        $i = 1;
                                        while ($data = db_fetch_array($sql)) {

                                        ?>

                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $data['name'] ?></td>
                                                <?php if (!$_GET['date']) { ?>
                                                    <td>

                                                        <a href="javascript:void(0);" onclick="change_goal('<?= $data['id'] ?>','<?= date('Y-m-d') ?>')"><?= getSingleresult("select count(*) from orders where orders.created_by='" . $data['id'] . "' and (date(orders.created_date)='" . date('Y-m-d') . "' or date(orders.convert_date)='" . date('Y-m-d') . "') and is_dr=1"); ?></a>
                                                    </td>
                                                <?php } else { ?>

                                                    <td>

                                                        <a href="javascript:void(0);" onclick="change_goal('<?= $data['id'] ?>','<?= $_GET['date'] ?>')"><?= getSingleresult("select count(*) from orders where orders.created_by='" . $data['id'] . "' and (date(orders.created_date)='" . $_GET['date'] . "' or date(orders.convert_date)='" . $_GET['date'] . "') and orders.is_dr=1") ?></a></td>
                                                <?php } ?>

                                                <?php if (!$_GET['date']) { ?>
                                                    <td> <?= getSingleresult("select count(*) from activity_log where added_by='" . $data['id'] . "' and date(activity_log.created_date)='" . date('Y-m-d') . "'"); ?></td>
                                                <?php } else { ?>

                                                    <td><?= getSingleresult("select count(*) from activity_log where added_by='" . $data['id'] . "' and date(activity_log.created_date)='" . $_GET['date'] . "'") ?></td>
                                                <?php } ?>

                                            </tr>

                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <div id="myModal" class="modal fade" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
        <script>
            // $(document).ready(function() {
            //     $('#myTable').DataTable();
            //     $(document).ready(function() {
            //         var table = $('#example').DataTable({
            //             "columnDefs": [{
            //                 "visible": false,
            //                 "targets": 2
            //             }],
            //             "order": [
            //                 [2, 'desc']
            //             ],
            //             buttons: [
            //                 'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            //             ],
            //             lengthMenu: [
            //                 [10, 25, 50, 100, 500, 1000],
            //                 ['10', '25', '50', '100', '500', '1000']
            //             ],
            //             "displayLength": 25,
            //             "drawCallback": function(settings) {
            //                 var api = this.api();
            //                 var rows = api.rows({
            //                     page: 'current'
            //                 }).nodes();
            //                 var last = null;
            //                 api.column(2, {
            //                     page: 'current'
            //                 }).data().each(function(group, i) {
            //                     if (last !== group) {
            //                         $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
            //                         last = group;
            //                     }
            //                 });
            //             }
            //         });
            //         // Order by the grouping
            //         $('#example tbody').on('click', 'tr.group', function() {
            //             var currentOrder = table.order()[0];
            //             if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            //                 table.order([2, 'desc']).draw();
            //             } else {
            //                 table.order([2, 'asc']).draw();
            //             }
            //         });
            //     });
            // });
            $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                <?php }else{ ?> 'pageLength'  <?php } ?>
                ],
                        lengthMenu: [
                            [10, 25, 50, 100, 500, 1000],
                            ['10', '25', '50', '100', '500', '1000']
                        ],
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
            $('#datepicker_autoclose').datepicker({
                format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

            });
        });

            function clear_search() {
                window.location = 'dv_report.php';
            }

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#leads").tableHeadFixer();

            });
        </script>