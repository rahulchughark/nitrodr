<?php include('includes/header.php');
admin_protect();
include_once('helpers/DataController.php');
$modify_log = new DataController();


if (isset($_POST['save_data'])) {

    try {
        if (!isset($_FILES['attachment'])) {
            throw new Exception("No file uploaded.");
        }

        $file = $_FILES['attachment'];
        $maxsize = 52428800000; // 500MB
        $target_dir = "learning_centre/videos/";
        $target_file = $target_dir . time() . basename($file["name"]);
        // Validate file upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new Exception("File too large. Maximum allowed size is 500MB.");
                        case UPLOAD_ERR_NO_FILE:
                            throw new Exception("No file uploaded.");
                            default:
                            throw new Exception("Unknown error during file upload.");
                        }
                    }
                    
        // Validate file extension
        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("mp4", "avi", "3gp", "mov", "mpeg");

        if (!in_array($extension, $valid_extensions)) {
            throw new Exception("Invalid file extension. Allowed extensions are: " . implode(", ", $valid_extensions));
        }

        // Validate file size
        if ($file['size'] > $maxsize) {
            throw new Exception("File too large. Maximum allowed size is 500MB.");
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            throw new Exception("Failed to upload the file.");
        }

        // Insert into the database
        $query = db_query("INSERT INTO training_videos(module_id, vdo_address) VALUES(1, '" . $target_file . "')");
        $video_id = get_insert_id($query);

        if (!$video_id) {
            throw new Exception("Failed to save video details in the database.");
        }

        $_SESSION['message'] = "Upload successful.";
        $admins = db_query("SELECT id from users where user_type in ('ADMIN','SUPERADMIN')");
        while ($adm = db_fetch_array($admins)) {
            $admm[] = $adm['id'];
        }
        // Log additional details
        $log = [
            'title'       => $_POST['title'],
            'description' => htmlspecialchars($_POST['desc'], ENT_QUOTES),
            'video_id'    => $video_id,
            'users_access'=> implode(",",$admm),
            'type'        => 'Video'
        ];

        $res = $modify_log->insert($log, "learning_zone");
        if (!$res) {
            throw new Exception("Failed to log the upload in the learning zone.");
        }

        echo "<script type=\"text/javascript\">
            window.location = \"learning_zone.php\";
        </script>";

    } catch (Exception $e) {
        // Handle exceptions and show the error message
        $_SESSION['message'] = $e->getMessage();
    }
}


if ($_POST['eid']) { 
    $res = db_query("update  `learning_zone` set `title`='" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', `description`='" . htmlspecialchars($_POST['desc'], ENT_QUOTES) . "' where type='Video' and id=" . $_POST['eid']);

    if ($res) {
        redir("learning_zone.php?update=success", true);
    }
} 

?>

<style>
    .dropdown-sm .dropdown-menu1 {
        max-width: 400px;
    }
</style>

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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home > Learning Centre</small>
                                            <h4 class="font-size-14 m-0 mt-1">Learning Zone</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group" role="group">
                                        <a href="javascript:void(0);" onclick="show_model()"><button title="Add Data" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>

                                        <!-- <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-- <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button> -->

                                    <!-- <div class="dropdown dropdown-sm">
                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">          
                                                <div class="form-group">
                                                    <select name="dtype" class="form-control" id="date_type">
                                                        <option value="">Select Date Type</option>
                                                        <option value="created">Created Date</option>
                                                        <option value="actioned_date">Actioned Date</option>
                                                        <option value="close">Close Date</option>
                                                        <option value="stage">Stage Change</option>
                                                        <option value="lead_status">Lead Status Change</option>
                                                    </select>
                                                </div>                                    
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div> 
                                            </form>
                                        </div>
                                    </div> -->
                                </div>
                            </div>


                            <div class="clearfix"></div>
                            

                            <!-- <h5 class="card-subtitle" style="text-align: center; text-align: center; font-size:large">Learning Zone</h5> -->

                            <div class="table-responsive" id="MyDiv">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">Date of upload</th>
                                            <th data-sortable="true">Title</th>
                                            <th data-sortable="true">Play</th>
                                            <th data-sortable="true">About Tutorial</th>
                                            <th data-sortable="true">Views</th>
                                            <th data-sortable="true">Edit/Delete</th>
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
                    url: "get_learning_zone.php", // json datasource
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


           function videoModel(video_id, video_address, title, view = ''){
            $.ajax({
                    type: 'POST',
                    url: 'video_learning_zone.php',
                    data: {
                        video_id: video_id,
                        video_address: video_address,
                        view: view,
                        title:title
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
           }


            function show_model() {
                $.ajax({
                    type: 'POST',
                    url: 'add_learning_zone.php',
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function edit_learning_zone(id) {
                $.ajax({
                    type: 'POST',
                    url: 'edit_learning_zone.php',
                    data : {
                       edit_id : id
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function count_views(video_id, video_address, title, view = '') {
                // alert(video_address),
                var url = '<?php define('MY_PATH', 'http://' . $HTTP_HOST . SITE_SUB_PATH) ?>';
                //alert(url),
                $.ajax({
                    type: 'POST',
                    url: 'count_views.php',
                    data: {
                        video_id: video_id,
                        video_address: video_address,
                        view: view,
                        title:title
                    },
                    success: function(data) {

                        var newWindow = window.open("", "_blank");
                        newWindow.location.href = url + video_address;
                        setTimeout(function() {
                            location.reload();
                        }, 1000)
                    }
                });
            }

            function delete_notification(id) {
                swal({
                    title: "Are you sure?",
                    text: "You want to remove video?",
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
                                delete_vid: id,
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Video deleted successfully!");
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

            function notFound(){
                swal("File not found.");
            }


</script>


