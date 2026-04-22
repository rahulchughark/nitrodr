<?php include('includes/header.php');
admin_page();

if ($_POST['save']) {
    if (getSingleresult("select id from lead_goals where partner_id=" . $_POST['pid'])) {
        $ins = db_query("update lead_goals set daily='" . $_POST['daily'] . "',weekly='" . $_POST['weekly'] . "',monthly='" . $_POST['monthly'] . "' where partner_id='" . $_POST['pid'] . "'");
        redir("manage_partners.php?goal=update", true);
    } else {
        $ins = db_query("insert into lead_goals (partner_id,daily,weekly,monthly) values ('" . $_POST['pid'] . "','" . $_POST['daily'] . "','" . $_POST['weekly'] . "','" . $_POST['monthly'] . "')");
        redir("manage_partners.php?goal=success", true);
    }
}

// filter results
 // salse manager
 if(isset($_GET['salse_manager'])){            
 $salse_managers = $_GET['salse_manager'];
    foreach($salse_managers as $salse_manager)
    {
       $salseManagerData =  explode(",",$salse_manager);
       // array data for auto select 
       $arrayData[] =  $salse_manager;
       foreach($salseManagerData as $value){
        $salseManagerIds[] = $value;
       }
    }

   $PartnerArr = (array_unique($salseManagerIds));
   }else{
    $PartnerArr = [];
    $arrayData = [];
   } 

// authorization
   if(isset($_GET['authorization'])){            
     $authorization = $_GET['authorization'];
       }else{
        $authorization = [];
       } 

// status

   if(isset($_GET['status'])){            
    $status = $_GET['status'];
   }else{
    $status = '';
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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Manage Partners</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage Partners</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                <div class="" role="group">

                        <?php if($_SESSION['user_type']!='ISS MNGR' || $_SESSION['user_type']!='OPERATIONS'){?>
                                <a href="add_partner.php"><button title="Add Partner" class="right-side bottom-right waves-effect waves-light btn-light btn btn-circle btn-md pull-right m-l-10"><i class="ti-plus"></i></button></a>

                        <?php } ?>

                                <!-- filter start -->
                                <?php
                                if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'){ ?>

                                    <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" name="search">
                                                <?php if (!is_array($partner)) {
                                                            $val = $partner;
                                                            $partner = array();
                                                            $partner['0'] = $val;
                                                            $partner_flag = 1;
                                                        } 


                                                $res = db_query("select id,name,access from users where sales_manager = 1 order by name asc"); ?>

                                                        <!-- <div class="form-group">
                                                        <select name="authorization[]" id="authorization" class="multiselect_authorization form-control" data-live-search="true" multiple>
                                                            <option <?= (in_array('Platinum', $authorization) ? 'selected' : '') ?> value="Platinum">Platinum</option>
                                                            <option <?= (in_array('Gold', $authorization) ? 'selected' : '') ?> value="Gold">Gold</option>
                                                            <option <?= (in_array('Silver', $authorization) ? 'selected' : '') ?> value="Silver">Silver</option>
                                                            <option <?= (in_array('ROI Gold', $authorization) ? 'selected' : '') ?> value="ROI Gold">ROI-Gold</option>
                                                            <option <?= (in_array('ROI Silver', $authorization) ? 'selected' : '') ?> value="ROI Silver">ROI-Silver</option>
                                                            <option <?= (in_array('Not Assigned', $authorization) ? 'selected' : '') ?> value="">Not Assigned</option>
                                                        </select>
                                                        </div> -->

                                                        <div class="form-group">
                                                            <select name="status" class="form-control" id="partner_status">
                                                            <option value="">Select Status</option>
                                                                <option <?= (($_GET['status'] == 'Active') ? 'selected' : '') ?> value="Active">Active</option>
                                                                <option <?= (($_GET['status'] == 'Inactive') ? 'selected' : '') ?> value="Inactive">Inactive</option>
                                                            </select>
                                                        </div>

                                                        <!-- <div class="form-group">
                                                            <select name="salse_manager[]" id="salse_manager" class="multiselect_salse_manager form-control" data-live-search="true" multiple>

                                                                <?php 
                                                                while ($row = db_fetch_array($res)) { 
                                                                    // print_r($arrayData);
                                                                    ?>
                                                                    <option <?= (in_array($row['access'], $arrayData) ? 'selected' : '') ?> value='<?= $row['access'] ?>'><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div> -->
                                                        
                                                                                                                        
                                                    <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                                </form>
                                            </div>
                                        </div>
                                            <?php } ?>  
                                <!-- filter end -->    



                                    </div>
                                </div>
                            </div>

                            

                        <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Partner Added Successfully!
                            </div>


                        <?php } ?>
                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Partner updated Successfully!
                            </div>


                        <?php } ?>

                        <?php if ($_GET['goal'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Goal Added Successfully!
                            </div>


                        <?php } ?>
                        <?php if ($_GET['goal'] == 'update') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Goal updated Successfully!
                            </div>


                        <?php } ?>
                        <div class="table-responsive">
                            <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                    <th data-sortable="true">S.No.</th>
                                        <th data-sortable="true">DR ID</th>
                                        
                                        <th data-sortable="true">VAR Name</th>
                                        <!-- <th data-sortable="true">Authorization</th> -->
                                        <!-- <th data-sortable="true" class="address_col">Address</th> -->
                                        <th data-sortable="true">City</th>
                                        <th data-sortable="true">State</th>
                                       
                                        <!-- <th data-sortable="true">Category</th>
                                        <th data-sortable="true">CDGS Target (Monthly)</th> -->
                                        <!-- <th data-sortable="true">Agreement</th> -->
                                        <th data-sortable="true" class="date_col">Created Date</th>
                                        <th data-sortable="true">Status</th>
                                        <th data-sortable="true">Action</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 

                                    $con = "";
                                     if(!empty($PartnerArr))
                                     {
                                        $PartnersIds = implode(",",$PartnerArr);
                                       $con .= " and partners.id in (".$PartnersIds.")"; 
                                     }

                                     if(!empty($authorization))
                                     {
                                        $authorization = implode("','",$authorization);
                                       $con .= " and partners.category in ('".$authorization."')"; 
                                     }

                                     if($status != '')
                                     {
                                       $status = $status;
                                       $con .= " and partners.status = '".$status."'"; 
                                     }

                                    if($_SESSION['sales_manager'] == 1){
                                        $sm_user = " and partners.sm_user =" . $_SESSION['user_id'];
                                    }
                                    if($_REQUEST['var']==1){
                                        $sql = db_query("select partners.*,states.name as state from partners join states on (partners.state=states.id) where 1 and partners.status='Active' $sm_user ".$con." order by partners.id desc");
                                    }else{
                                        $sql = db_query("select partners.*,states.name as state  from partners join states on (partners.state=states.id) where 1 ".$con." order by partners.id desc");
                                    }
                                    
                                     $i=1;
                                    while ($data = db_fetch_array($sql)) {
                                        //print_r(date('d-m-Y',strtotime($data['created']))); die;
                                    ?>

                                        <tr id="tr-id-1" class="tr-class-1">
                                        <td><?= $i?></td>
                                            <td id="td-id-1" class="td-class-1"><?= $data['0'] ?></td>
                                           <!--  <td id="td-id-1" class="td-class-1"><?= $data['2'] ?></td> -->
                                            <td><?=(($_SESSION['user_type']!='OPERATIONS' && $_SESSION['user_type']!='ISS MNGR')?'<a style="color: #000;" href="add_partner.php?eid='.$data['id']. '")>':'')?><?= $data['2'] ?></a></td>
                                            <!-- <td><?= $data['category'] ?></td> -->
                                            <!-- <td class="address_col"><?= $data['address'] ?></td> -->
                                            <td><?= $data['city'] ?></td>
                                            <td><?= $data['state'] ?></td>

                                           
                                            <!-- <td><?= $data['category'] ?></td> -->
                                            
                                            <!-- <td><?=($data['agreement']?'<a href="uploads/agreements/'.$data['agreement'].'" target="_blank">View</a>':'N/A') ?></td> -->
                                            <td class="date_col"><?= ($data['created']!= '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($data['created'])) :'NA') ?></td>
                                            <td><?= $data['status'] ?></td>
                                            <td><button class="btn btn-primary px-2 py-1" data-toggle="modal" data-target="#viewPartnerModal"><span class="mdi mdi-eye"></span></button></td>
                                        </tr>

                                    <?php $i++; } ?>
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
    <!-- Modal -->
    <div class="modal fade" id="viewPartnerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <form id="uploadForm" enctype="">
                    
                    <div class="modal-header">
                        <h5 class="modal-title align-self-center mt-0" id="modal-header-title">View Partner</h5>
                        <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.modal').modal('hide')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body py-4">
                        <form action="">
                            <ul class="file-tree move-category-tree">

                                <li class="folder">
                                    <div class="fi">
                                        <div class="custom-checkbox">
                                            <input type="checkbox" id="chk_all">
                                            <label for="chk_all">All</label>
                                        </div>
                                    </div>

                                    <ul>
                                        <!-- ================= DOCUMENTS ================= -->
                                        <li class="folder">
                                            <div class="fi">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="chk_documents" checked>
                                                    <label for="chk_documents">Documents</label>
                                                </div>
                                            </div>

                                            <ul>
                                                <!-- ===== D1 ===== -->
                                                <li class="folder">
                                                    <div class="fi">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" id="chk_documents_d1">
                                                            <label for="chk_documents_d1">D1</label>
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        <li>
                                                            <div class="content-category">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Document Name</th>
                                                                                <th>Type</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-checkbox">
                                                                                        <input type="checkbox" id="chk_doc_1" checked>
                                                                                        <label for="chk_doc_1"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>Document 1</td>
                                                                                <td>PDF</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-checkbox">
                                                                                        <input type="checkbox" id="chk_doc_2">
                                                                                        <label for="chk_doc_2"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>Document 2</td>
                                                                                <td>Word</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-checkbox">
                                                                                        <input type="checkbox" id="chk_doc_3" checked>
                                                                                        <label for="chk_doc_3"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>Document 3</td>
                                                                                <td>Excel</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <!-- ===== D2 ===== -->
                                                <li class="folder">
                                                    <div class="fi">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" id="chk_documents_d2">
                                                            <label for="chk_documents_d2">D2</label>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>

                                        <!-- ================= VIDEO ================= -->
                                        <li class="folder">
                                            <div class="fi">
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" id="chk_video">
                                                    <label for="chk_video">Video</label>
                                                </div>
                                            </div>

                                            <ul>
                                                <li class="folder">
                                                    <div class="fi">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" id="chk_video_1">
                                                            <label for="chk_video_1">Video 1</label>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="folder">
                                                    <div class="fi">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" id="chk_video_2">
                                                            <label for="chk_video_2">Video 2</label>
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        <li>
                                                            <div class="content-category">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Document Name</th>
                                                                                <th>Type</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-checkbox">
                                                                                        <input type="checkbox" id="chk_doc_1">
                                                                                        <label for="chk_doc_1"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>Document 1</td>
                                                                                <td>PDF</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-checkbox">
                                                                                        <input type="checkbox" id="chk_doc_2">
                                                                                        <label for="chk_doc_2"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>Document 2</td>
                                                                                <td>Word</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="custom-checkbox">
                                                                                        <input type="checkbox" id="chk_doc_3">
                                                                                        <label for="chk_doc_3"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>Document 3</td>
                                                                                <td>Excel</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li class="folder">
                                                    <div class="fi">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" id="chk_video_3">
                                                            <label for="chk_video_3">Video 3</label>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>

                                    </ul>
                                </li>

                            </ul>


                            <div class="text-center">
                                <button class="btn btn-primary mx-1">Update</button>
                            </div>
                        </form>

                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php') ?>
    <script>
        // $(document).ready(function() {
        //     $('#myTable').DataTable();
        //     $(document).ready(function() {
        //         var table = $('#example').DataTable({
        //             "stateSave": true,
        //             "columnDefs": [{
        //                 "visible": false,
        //                 "targets": 2
        //             }],
        //             "order": [
        //                 [2, 'desc']
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
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
            ],
            "displayLength": 15,
            order: [[ 1, "desc" ]],
        });

        function change_goal(a) {
            $.ajax({
                type: 'POST',
                url: 'get_goal.php',
                data: {
                    pid: a
                },
                success: function(response) {
                    $("#myModal").html();
                    $("#myModal").html(response);
                    $('#myModal').modal('show');
                }
            });
        }
    </script>
    <script>
        function clear_search() {
                window.location = 'manage_partners.php';
            }

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#example23").tableHeadFixer();

            });

            $(document).ready(function() {
                $('.multiselect_salse_manager').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sales Manager'
                });
            });

            $(document).ready(function() {
                $('.multiselect_authorization').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Authorization Lavel'
                });
            });



    function viewManagePartner() {
        $.ajax({
            type: 'POST',
            url: 'view_manage_partners.php',
            data: {},
            success: function(response) {
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }
    </script>

    <script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ STOP checkbox + label bubbling
    document.querySelectorAll('.custom-checkbox input, .custom-checkbox label')
        .forEach(el => {
            el.addEventListener('click', e => e.stopPropagation());
        });

    // ✅ STOP content-category bubbling (NEW FIX)
    document.querySelectorAll('.content-category')
        .forEach(el => {
            el.addEventListener('click', e => e.stopPropagation());
        });

    // ✅ ADD end-folder class (leaf nodes)
    document.querySelectorAll('.file-tree .folder').forEach(folder => {
        if (!folder.querySelector(':scope > ul')) {
            folder.classList.add('end-folder');
        }
    });

    // ✅ OPEN ALL folders at start
    document.querySelectorAll('.file-tree .folder')
        .forEach(folder => folder.classList.add('active'));

    // ✅ Toggle logic (manual open/close)
    document.addEventListener("click", function (e) {
        const folder = e.target.closest(".file-tree .folder");
        if (!folder) return;

        // ❌ Do not toggle leaf folders
        if (folder.classList.contains('end-folder')) return;

        // ✅ Toggle only clicked folder
        folder.classList.toggle("active");
    });

});
</script>