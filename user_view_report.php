
<?php include('includes/header.php');
admin_protect();


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
                                    <small class="text-muted">Home > Learning Centre</small>
                                </div>
                            </div>


                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:-35px;">
                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                    </div>
                                </div>
                            </div>

                            <h5 class="card-subtitle" style="text-align: center; font-size:large">User Views Report</h5>

                            <div class="table-responsive" id="MyDiv">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">Viewer Name</th>
                                            <th data-sortable="true">Viewer Organization Name</th>
                                            <th data-sortable="true">Role</th>
                                            <th data-sortable="true">Content Viewed</th>
                                            <th data-sortable="true">Type</th>
                                            <th data-sortable="true">Date</th>
                                            <th data-sortable="true">Time</th>
                                            <!-- <th data-sortable="true">Video Duration</th> -->
                                            <!-- <th data-sortable="true">Video Played</th> -->
                                            <!-- <th data-sortable="true">Duration</th> -->
                                        </tr>
                                    </thead>
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
        <div id="notif_dropdown_usr" class="modal fade" role="dialog"></div>
        <?php include('includes/footer.php') ?>

        <script>
            $('#leads').DataTable({
                "stateSave": true,
                dom: 'Bfrtip',
                bSortCellsTop: true,
                //bSortCellsBottom: true,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000, 10000, 50000],
                    ['15', '25', '50', '100', '500', '1000', '10000', '50000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_user_view.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.partner = '<?= @implode('","', $_GET['partner']) ?>';
                        d.date_from = '<?= $_GET['date_from'] ?>';
                        d.date_to = '<?= $_GET['date_to'] ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");
                    }
                },
            });


            function count_views(video_id,video_address,title,view = '') {
               // alert(video_address),
               var url = '<?php define('MY_PATH', 'http://'.$HTTP_HOST.SITE_SUB_PATH) ?>';
               //alert(url),
                $.ajax({
                    type: 'POST',
                    url: 'count_views.php',
                    data: {
                        video_id      : video_id,
                        video_address : video_address, 
                        title         : title,  
                        view          : view,                             
                    },
                    success: function(data) {               
                    var newWindow = window.open("", "_blank");
                    newWindow.location.href = url+video_address;
                    // setTimeout(function() {
                    //         location.reload();
                    //     }, 1000)
                    }
                });
            }

            function clear_search() {
                window.location = 'learning_zone_partner.php';
            }

            function duration_views() {
                $.ajax({
                    type: 'POST',
                    url: 'count_views.php',
                    data: {
                        view : 'view',                             
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 350);
                $("#leads").tableHeadFixer();

            });
        </script>