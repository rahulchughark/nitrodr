<?php include('includes/header.php');
admin_protect();
include_once('helpers/DataController.php');
$modify_log = new DataController();


if (isset($_POST['save_activity'])) {
    $log = [
        'module_name' => htmlspecialchars($_POST['l_module'], ENT_QUOTES),
        'url'         => $_POST['l_url'],
        'status'      => intval($_POST['status']),
    ];

    $res = $modify_log->insert($log, "learning_centre_module");

    if ($res) {
        redir("learning_centre.php?update=success", true);
    }
}

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
                                <a href="javascript:void(0);" onclick="show_model()"><button title="Add Data" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>

                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="learning_centre_module_outer row">
                                <?php $sql = db_query("select * from learning_centre_module where status=1");
                                while ($data = db_fetch_array($sql)) { ?>
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                        <h5><a href="<?= $data['url'] ?>" class="btn btn-primary w-full" target="_blank"><?= $data['module_name'] ?></a></h5>
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
            function show_model() {
                $.ajax({
                    type: 'POST',
                    url: 'add_learning_centre.php',
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function clear_search() {
                window.location = 'learning_centre.php';
            }
        </script>

        <script>
            $(document).ready(function () {
                var wfheight = $(window).height();

                if ($(window).width() <= 768) {
                    // Mobile view
                    $('.admin_kra1').height(wfheight - 130); // Adjust height for mobile
                } else {
                    // Desktop view
                    $('.admin_kra1').height(wfheight - 165); // Adjust height for desktop
                }

                $("#leads").tableHeadFixer();
            });
        </script>