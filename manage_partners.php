<?php 

include('includes/header.php');
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

<style>
    .file-tree > ul {
    display: block !important;
} 

.content-category {
    max-height: 280px;   /* adjust height as needed */
    overflow-y: auto;
}

/* Optional: smooth scrolling */
.content-category {
    scrollbar-width: thin;
}

.view-btn {
    padding: 4px 10px !important;
    font-size: 12px !important;
    line-height: 1.2 !important;
    border-radius: 8px;
    height: auto;
}

.setting-rotate {
    animation: spin 1s linear infinite;
}

.btn-processing {
    pointer-events: none;   /* prevents click */
    opacity: 0.7;
    cursor: pointer;        /* keeps normal cursor */
}


li.folder > ul {
    display: none;
}
li.folder.open > ul {
    display: block;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
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

                        <?php if(($_SESSION['user_type']!='ISS MNGR' || $_SESSION['user_type']!='OPERATIONS') && $_SESSION['user_type']!='AE'){?>
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
                                        <th data-sortable="true">Total Users</th>
                                        <th data-sortable="true">Active Users</th>
                                        <th data-sortable="true">Inactive Users</th>
                                       
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
                                        $sql = db_query("select partners.*,states.name as state, COUNT(users.id) as total_users, SUM(CASE WHEN users.status='Active' THEN 1 ELSE 0 END) as active_users, SUM(CASE WHEN users.status in ('Inactive','InActive') THEN 1 ELSE 0 END) as inactive_users from partners join states on (partners.state=states.id) left join users on users.team_id=partners.id where 1 and partners.status='Active' $sm_user ".$con." group by partners.id order by partners.id desc");
                                    }else{
                                        $sql = db_query("select partners.*,states.name as state, COUNT(users.id) as total_users, SUM(CASE WHEN users.status='Active' THEN 1 ELSE 0 END) as active_users, SUM(CASE WHEN users.status in ('Inactive','InActive') THEN 1 ELSE 0 END) as inactive_users from partners join states on (partners.state=states.id) left join users on users.team_id=partners.id where 1 ".$con." group by partners.id order by partners.id desc");
                                    }
                                    
                                     $i=1;
                                    while ($data = db_fetch_array($sql)) {
                                        //print_r(date('d-m-Y',strtotime($data['created']))); die;
                                    ?>

                                        <tr id="tr-id-1" class="tr-class-1">
                                        <td><?= $i?></td>
                                            <td id="td-id-1" class="td-class-1"><?= $data['0'] ?></td>
                                           <!--  <td id="td-id-1" class="td-class-1"><?= $data['2'] ?> </td> -->
                                           
                                           <td>
                                            <?php if (
                                                $_SESSION['user_type'] != 'OPERATIONS' &&
                                                $_SESSION['user_type'] != 'ISS MNGR' &&
                                                $_SESSION['user_type'] != 'AE'
                                            ) { ?>
                                                <a style="color:#000;" href="add_partner.php?eid=<?= $data['id'] ?>">
                                                    <?= $data[2] ?>
                                                </a>
                                            <?php } else { ?>
                                                <?= $data[2] ?>
                                            <?php } ?>
                                            </td>
                                            <!-- <td><?= $data['category'] ?></td> -->
                                            <!-- <td class="address_col"><?= $data['address'] ?></td> -->
                                            <td><?= $data['city'] ?></td>
                                            <td><?= $data['state'] ?></td>
                                            <td><a target="_blank" href="manage_users_admin.php?status=&partnersId%5B%5D=<?= $data['id'] ?>"><?= (int)($data['total_users'] ?? 0) ?></a></td>
                                            <td><a target="_blank" href="manage_users_admin.php?status=Active&partnersId%5B%5D=<?= $data['id'] ?>"><?= (int)($data['active_users'] ?? 0) ?></a></td>
                                            <td><a target="_blank" href="manage_users_admin.php?status=Inactive&partnersId%5B%5D=<?= $data['id'] ?>"><?= (int)($data['inactive_users'] ?? 0) ?></a></td>

                                           
                                            <!-- <td><?= $data['category'] ?></td> -->
                                            
                                            <!-- <td><?=($data['agreement']?'<a href="uploads/agreements/'.$data['agreement'].'" target="_blank">View</a>':'N/A') ?></td> -->
                                            <td class="date_col"><?= ($data['created']!= '0000-00-00 00:00:00' ? date('d-m-Y', strtotime($data['created'])) :'NA') ?></td>
                                            <td><?= $data['status'] ?></td>
                                          
                                            <td>
                                                <a title="Edit Partner" href="add_partner.php?eid=<?= $data['id'] ?>" class="btn btn-primary px-2 py-1"><span class="mdi mdi-pencil"></span></a>
                                            </td>
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
                        <h5 class="modal-title align-self-center mt-0" id="modal-header-title">View <span id="partner-label-name"></span></h5>
                        <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.modal').modal('hide')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body py-4">
                        <form action="">
                            <!-- style="display: block !important;" -->
                           <ul id="partner-access-tree" class="file-tree move-category-tree" >
                           </ul>

                            <!-- <div class="text-center">
                                <button class="btn btn-primary mx-1">Update</button>
                            </div> -->
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

        // function visitingCard(partnerId) {
        //         const pdfWindow = window.open(
        //             'partner_pdf_generator.php?partner_id=' + partnerId,
        //             '_blank'
        //         );

        //         // Reload current page after short delay
        //         setTimeout(function () {
        //             window.location.reload();
        //         }, 1000); // 1 second is safe for PDF generation
        //     }

        // function generateVisitingCard(partnerId) {
        //         $.ajax({
        //             url: 'partner_pdf_generator.php',
        //             type: 'GET',
        //             data: { partner_id: partnerId },
        //             dataType: 'json',
        //             success: function (res) {
        //                 if (res.status === 'success') {
        //                     // Open PDF
        //                     // window.open(res.pdf_path, '_blank');

        //                     // Reload current page
        //                     location.reload();
        //                 }
        //             },
        //             error: function () {
        //                 alert('Failed to generate visiting card');
        //             }
        //         });
        //     }

        function generateVisitingCard(btn) {
                const partnerId = btn.dataset.id;
                const icon = btn.querySelector('.setting-icon');
                btn.classList.add('btn-processing');


                btn.disabled = true;
                icon.classList.add('setting-rotate');

                $.ajax({
                    url: 'partner_pdf_generator.php',
                    type: 'GET',
                    data: { partner_id: partnerId },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status === 'success') {
                            location.reload();
                        } else {
                            stopLoader(btn, icon);
                        }
                    },
                    error: function () {
                        stopLoader(btn, icon);
                    }
                });
            }


       function viewVisitingCard(path) {

            if (!path) {
                alert('PDF not found');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'visiting_card.php',
                data: { pdf_path: path },
                success: function (response) {
                    $("#myModal").html(response);
                    $("#myModal").modal('show');
                    $('#visitingCardPdf').attr('src', pdfPath + '#toolbar=0');
                },
                error: function () {
                    alert("Error loading modal");
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


viewPartnerDetailID = 0;

function viewPartnerDetails(partnerId,partnerName) {
    $("#partner-label-name").text(partnerName);
    viewPartnerDetailID = partnerId;
    $.ajax({
        type: 'POST',
        url: 'partner_folder_access.php',
        data: {
            pid: partnerId
        },
        success: function(response) {
            $("#partner-access-tree").html(response);
            // $('#viewPartnerModal').modal('show');
        }
    });
}


function updateCategoryAccess(el) {

    const categoryId = $(el).data('id');
    const categoryName = $(el).data('value');
    const isChecked  = el.checked ? 1 : 0;
    const partnerId  = viewPartnerDetailID;

    
    const actionText = isChecked
        ? "grant access to this Folder?"
        : "remove access from this Folder?";

    const previousState = !el.checked;

    swal({
        title: "Are you sure?",
        text: "Do you want to " + actionText,
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Confirm",
        confirmButtonColor: "#28a745",
        closeOnConfirm: false
    }, function () {
        $.ajax({
            url: "ajax_update_partner_category.php",
            type: "POST",
            dataType: "json",
            data: {
                category_id: categoryId,
                partner_id: partnerId,
                category_name: categoryName,
                checked: isChecked
                
            },
            success: function (response) {

                if (response.status === "success") {
                    swal("Success!", response.message, "success");
                } else {
                    swal("Error!", response.message || "Update failed", "error");
                    el.checked = previousState; // rollback
                }
            },
            error: function () {
                swal("Error!", "Server error occurred.", "error");
                el.checked = previousState; // rollback
            }
        });

    }, function () {
        el.checked = previousState;
    });
}


function handleCategoryCheckbox(el) {

    const attachmentId = el.value;
    const partnerId    = el.dataset.partner;
    const isChecked    = el.checked;

    $.ajax({
        url: 'ajax_partner_file_access.php',
        type: 'POST',
        dataType: 'json',
        data: {
            attachment_id: attachmentId,
            partner_id: partnerId,
            action: isChecked ? 'add' : 'remove'
        },
        success: function (res) {
            if (res.status === 'success') {
                toastr.success(res.message);
            } else {
                toastr.info(res.message);
            }
        },
        error: function () {
            toastr.error('Server error');
        }
    });
}


</script>
<script>
document.addEventListener('click', function (e) {

    const folderHeader = e.target.closest('.fi');
    if (!folderHeader) return;

    const currentLi = folderHeader.closest('li.folder');
    if (!currentLi) return;

    // close all other open folders
    document.querySelectorAll('li.folder.open').forEach(li => {
        if (li !== currentLi) {
            li.classList.remove('open');
            const ul = li.querySelector(':scope > ul');
            if (ul) ul.style.display = 'none';
        }
    });

    // toggle current folder
    currentLi.classList.toggle('open');
    const childUl = currentLi.querySelector(':scope > ul');
    if (childUl) {
        childUl.style.display = currentLi.classList.contains('open') ? 'block' : 'none';
    }
});
</script>