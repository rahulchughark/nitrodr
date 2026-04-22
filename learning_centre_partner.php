<?php include('includes/header.php');

?>

<style>
    .w-full {
        width: 100%;
    }
</style>


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content learning-centre-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body admin_kra1">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > Learning Centre</small>
                                    <h4 class="font-size-14 m-0 mt-1">Learning Centre</h4>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:-35px;">


                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="learning_centre_module_outer row">
                            <?php $sql = db_query("select * from learning_centre_module where status=2");
                            while ($data = db_fetch_array($sql)) { ?>
                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                    <h5><a  href="<?= $data['url'] ?>" class="btn btn-primary w-full" target="_blank"><?= $data['module_name'] ?></a></h5>
                                </div>
                            <?php } ?>
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
            function clear_search() {
                window.location = 'learning_centre_partner.php';
            }
        </script>
          <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                //$('.dataTables_wrapper').height(wfheight - 370);
				$('.admin_kra1').height(wfheight - 165);
				
                $("#leads").tableHeadFixer();

            });
        </script>