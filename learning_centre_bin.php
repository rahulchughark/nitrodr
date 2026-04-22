<?php include('includes/header.php');
admin_protect();
include_once('helpers/DataController.php');
$modify_log = new DataController();


if (isset($_POST['save_data'])) {
    // print_r($_POST);   
    $maxsize = 52428800000; // 500MB
    if ($_FILES["attachment"]) {
        // print_r($_POST);
        $name = $_FILES['attachment']['name'];

        $target_dir = "learning_centre/videos/";
        $target_file = $target_dir . basename($_FILES["attachment"]["name"]);

        // Select file type
        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("mp4", "avi", "3gp", "mov", "mpeg");

        // Check extension
        if (in_array($extension, $extensions_arr)) {
            // Check file size
            if (($_FILES['attachment']['size'] >= $maxsize) || ($_FILES["attachment"]["size"] == 0)) {

                $_SESSION['message'] = "File too large. File must be less than 5MB.";
            } else {
                // Upload
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                    // Insert record               
                    $query =  db_query("INSERT INTO training_videos(module_id,vdo_address) VALUES(1,'" . $target_file . "')");
                    // print_r($query);
                    $video_id = get_insert_id($query);
                    $_SESSION['message'] = "Upload successfully.";
                }
            }
        } else {
            $_SESSION['message'] = "Invalid file extension.";
        }
    } else {
        $_SESSION['message'] = "Please select a file.";
    }

    $log = [
        'title'       => $_POST['title'],
        'description' => htmlspecialchars($_POST['desc'], ENT_QUOTES),
        'video_id'    => $video_id,
        'type'        => 'Video'
    ];

    $res = $modify_log->insert($log, "learning_zone");
    if ($res) {
        echo "<script type=\"text/javascript\">
          window.location = \"learning_zone.php\"
        </script>";
        //redir("learning_zone.php?update=success", true);
    }
}


if ($_POST['eid']) { 
    $res = db_query("update  `learning_zone` set `title`='" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', `description`='" . htmlspecialchars($_POST['desc'], ENT_QUOTES) . "' where type='Video' and id=" . $_POST['eid']);

    if ($res) {
        redir("learning_zone.php?update=success", true);
    }
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
                                    <small class="text-muted">Home > Learning Centre</small>
                                    <h4 class="font-size-14 m-0 mt-1">Recycle Bin
                                    </h4>
                                </div>
                            </div>


                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:-35px;">
                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="MyDiv">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">Date of upload</th>
                                            <th data-sortable="true">Title</th>
                                            <th data-sortable="true">Restore</th>
                                            <th data-sortable="true">About Tutorial</th>
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
                    url: "get_learning_recycleBin.php", // json datasource
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


            function restore(id) {
                swal({
                    title: "Are you sure?",
                    text: "You want to restore data?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                            type: 'POST',
                            url: 'count_views.php',
                            data: {
                                restore_id: id,
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Data restored successfully!");
                            setTimeout(function() {
                                location.reload();
                            }, 1000)
                        })
                        .error(function(data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
                })
            }

            function clear_search() {
                window.location = 'learning_zone.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#leads").tableHeadFixer();

            });
        </script>