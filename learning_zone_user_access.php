<?php include('includes/header.php');
admin_protect();
if($_POST['edit_access']){
    // $alreadyUsers = db_fetch_array(db_query("select * from learning_zone where id=".$_POST['pid']));
    // $alreadyUsersTypes = explode(",",$alreadyUsers['users_access']);
    // $notInArray1 = array_diff($_POST['usersTypes'], $alreadyUsersArr);
    // if (!empty($notInArray1)) {
    // $addCc[] = $_SESSION['email'];
    // $manager_email=db_query("select email from users where id in (".implode(",",$notInArray1).")");
    // while($me=db_fetch_array($manager_email))
    // {
    //     $addTo[] = $me['email'];
    // }
    // $setSubject = ($alreadyUsers['title'] . " - Marketing Material Access Provided.");
    // $body    = "Hello,<br><br> Marketing Material - " . $alreadyUsers['title'] . " access provided on ICT DR Portal.<br><br>
    // Thanks,<br>
    // ICT DR Portal
    // ";
    // $addBcc[] = '';
    // sendMail($addTo, $addCc, $addBcc, $setSubject, $body ,$attachment);
    // }

    $usrs = empty($_POST['usersTypes']) ? 0 : implode(",",$_POST['usersTypes']);
    $status=db_query("update learning_zone set users_access = '".$usrs."' where id=".$_POST['pid']);
    // print_r($status);die;
    if($status){
        redir("learning_zone_user_access.php?update=success", true);
    }
}
?>

<style>
    .modal-body {
        min-height: 320px;
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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > Learning Zone > Users Access List</small>
                                </div>
                            
                            <div class="col-auto">
                                    <div class="btn-group" role="group">
                                        <!-- <a href="javascript:void(0);" onclick="show_model()"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Data" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a> -->

                                        <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                    <div class="dropdown dropdown-sm">
                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">          
                                                <div class="form-group col-md-4">
                                                    <select name="type" class="form-control" id="date_type">
                                                        <option value="">Select record type</option>
                                                        <option <?= (($_GET['type'] == 'Doc') ? 'selected' : '') ?> value="Doc">Document</option>
                                                        <option <?= (($_GET['type'] == 'Video') ? 'selected' : '') ?> value="Video">Video</option>
                                                    </select>
                                                </div>                                    
                                                <div class="form-group col-md-4">
                                                    <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div> 
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->

                            <div class="clearfix"></div>
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success d-flex pl-4 pt-4 pr-4 rounded-3 w-100" role="alert">
                                <div class="flex-grow-1">
                               
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
                                <div class="mb-3"> Product Document Added Successfully!</div>
                              
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success d-flex pl-4 pt-4 pr-4 rounded-3 w-100" role="alert">
                                <div class="flex-grow-1">
                               
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3>
                                <div class="mb-3">Updated Successfully!</div>
                              
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </div>
                            <?php } ?>

                            <div class="table-responsive" id="MyDiv">
                                <table id="leads" class="table display nowrap table-striped text-center" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">Title</th>
                                            <th data-sortable="true">Type</th>
                                            <th data-sortable="true">Users</th>
                                            <th data-sortable="true">Action</th>
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
                    url: "get_learning_zone_user_access.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.type = "<?= $_GET['type'] ?>";
                    },

                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
            });

            function show_model() {
                $.ajax({
                    type: 'POST',
                    url: 'add_product_document.php',
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function edit_product_doc(id) {
                $.ajax({
                    type: 'POST',
                    url: 'edit_product_doc.php',
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

            function notFound(){
                swal("File not found.");
            }

            function count_views(document, id) {
                var url = '<?php define('MY_PATH', 'http://' . $HTTP_HOST . SITE_SUB_PATH) ?>';
                $.ajax({
                    type: 'POST',
                    url: 'count_views.php',
                    data: {
                        document: document,
                        product_id: id,
                    },
                    success: function(data) {
                        // var newWindow = window.open("", "_blank");
                        window.location.href = url + document;
                        setTimeout(function() {
                            location.reload();
                        }, 1000)
                    }
                });
            }

            function clear_search() {
                window.location = 'learning_zone_user_access.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 305);
                $("#leads").tableHeadFixer();

            });

            function delete_notification(id) {
                swal({
                    title: "Are you sure?",
                    text: "You want to remove document?",
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
                                delete_docId: id,
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Document deleted successfully!");
                            setTimeout(function() {
                                location.reload();
                            }, 1000)
                        })
                        .error(function(data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
                })
            }

        function change_access(id) {
            $.ajax({
                type: 'POST',
                url: 'change_user_access_learning_zone.php',
                data: {
                    pid: id
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