<?php

include('includes/header.php');
admin_protect();

?>
<style>
    .document-catgeory {
        max-width: 260px;
        height: 30px;
        padding: 5px 10px;
    }

    .dataTables_filter {
        position: absolute;
        top: 45px;
        right: 15px;
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
                                    <small class="text-muted">Home > Product Document</small>
                                    <h4 class="font-size-14 m-0 mt-1">Product Document</h4>
                                </div>
                            </div>


                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:-35px;">
                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                    </div>
                                </div>
                            </div>

                            <div class="custom-tabs mt-3">
                                <div class="row align-items-end">
                                    <div class="col"> 
                                        <ul class="nav nav-tabs mb-0" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="active" id="po-tab" data-toggle="tab" href="#productDoc" role="tab" aria-controls="po" aria-selected="true">Document</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a id="pi-tab" data-toggle="tab" href="#productVideo" role="tab" aria-controls="pi" aria-selected="false">Video</a>
                                            </li>
                                             <div class="position-relative search-category-DO">
                                                <button type="button" class="btn btn-xs btn-light ml-1" id="filter-box">
                                                    Select Category
                                                </button>
                                                <div class="">
                                                    <div class="dropdown-menu1 dropdown-md filter_wrap_2 dropdown-menu-right-xs" id="filter-container" role="menu">

                                                        <div class="form-group">
                                                            <input type="text" onkeyup="return categorySearch(event)" class="form-control" placeholder="Search Category">
                                                        </div>
                                                        
                                                        <div class="docCatTree" id="category-filter-html">
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- <select id="document_catgeory" name="document_catgeory" class="form-control document-catgeory ml-1">
                                            <option>Select Category</option>
                                            <option>Product Pitch and Marketing Documents</option>
                                            <option>Commercial Documents</option>
                                            <option>Technical and Operational Documents</option>
                                            <option>Training and Support Resources</option>
                                            <option>Optional / Value Add Resources</option>
                                            <option>ATL</option>
                                        </select> -->
                                        </ul>   
                                    </div>
                                    <!-- <div class="col-auto">

                                    </div> -->
                                </div>
                                <div class="tab-content pt-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="productDoc" role="tabpanel" aria-labelledby="doc-tab">                                
                                        <div class="table-responsive" id="MyDiv">
                                            <table id="documents" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-sortable="true">S.No.</th>
                                                        <th data-sortable="true">Date of upload</th>
                                                        <!-- <th data-sortable="true">Title</th> -->
                                                        <th data-sortable="true">View</th>
                                                        <th data-sortable="true">Document Category</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="productVideo" role="tabpanel" aria-labelledby="video-tab">
                                        <div class="table-responsive" id="MyDiv">
                                            <table id="videos" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-sortable="true">S.No.</th>
                                                        <th data-sortable="true">Date of upload</th>
                                                        <!-- <th data-sortable="true">Title</th> -->
                                                        <th data-sortable="true">View</th>
                                                        <th data-sortable="true">Document Category</th>
                                                     </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>                                    
                                </div>
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
let documentPartnerTable;
let videoPartnerTable;

function initializeDocumentPartnerTable(selectedCategory = '') {
    if ($.fn.DataTable.isDataTable('#documents')) {
        documentPartnerTable.clear().destroy();
    }

    documentPartnerTable = $('#documents').DataTable({
        stateSave: true,
        dom: 'frtip',
        bSortCellsTop: true,
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        // buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
        lengthMenu: [
            [15, 25, 50, 100, 500, 1000, 10000, 50000],
            ['15', '25', '50', '100', '500', '1000', '10000', '50000']
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "get_product_doc_partner.php",
            type: "post",
            data: function (d) {
                d.d_from = "<?= $_GET['d_from'] ?>";
                d.d_to = "<?= $_GET['d_to'] ?>";
                d.partner = '<?= safe_implode('","', $_GET['partner']) ?>';
                d.date_from = '<?= $_GET['date_from'] ?>';
                d.date_to = '<?= $_GET['date_to'] ?>';
                d.document_category = selectedCategory;
            },
            error: function () {
                $(".employee-grid-error").html("");
                $("#documents").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                $("#documents_processing").css("display", "none");
            }
        }
    });

    $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 370);
                $("#documents").tableHeadFixer();

            });
}

function initializeVideoPartnerTable(selectedCategory = '') {
    if ($.fn.DataTable.isDataTable('#videos')) {
        videoPartnerTable.clear().destroy();
    }

    videoPartnerTable = $('#videos').DataTable({
        stateSave: true,
        dom: 'frtip',
        bSortCellsTop: true,
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        // buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
        lengthMenu: [
            [15, 25, 50, 100, 500, 1000, 10000, 50000],
            ['15', '25', '50', '100', '500', '1000', '10000', '50000']
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "get_learning_zone_partner.php",
            type: "post",
            data: function (d) {
                d.d_from = "<?= $_GET['d_from'] ?>";
                d.d_to = "<?= $_GET['d_to'] ?>";
                d.partner = '<?= safe_implode('","', $_GET['partner']) ?>';
                d.date_from = '<?= $_GET['date_from'] ?>';
                d.date_to = '<?= $_GET['date_to'] ?>';
                d.document_category = selectedCategory;
            },
            error: function () {
                $(".employee-grid-error").html("");
                $("#videos").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                $("#videos_processing").css("display", "none");
            }
        }
    });

       $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 370);
                $("#videos").tableHeadFixer();

            });
}

// On page load
initializeDocumentPartnerTable();
initializeVideoPartnerTable();

// On dropdown change
$('#document_catgeory').on('change', function () {
    const selectedValue = $(this).val();
    initializeDocumentPartnerTable(selectedValue);
    initializeVideoPartnerTable(selectedValue);
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

            function count_views(document, id) {
                var url = '<?php define('MY_PATH', 'http://' . $HTTP_HOST . SITE_SUB_PATH) ?>';
                //alert(url),
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
                window.location = 'product_doc_partner.php';
            }
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 370);
                $("#documents").tableHeadFixer();

            });
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 370);
                $("#videos").tableHeadFixer();

            });
            
            function notFound(){
                swal("File not found.");
            }

                        function videoModelV(video_id, video_address, title, view = ''){
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


             function document_view(id) {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'document_view.php',
                    data: { page_access: page_access,id:id },
                    success: function(response) {
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            $("#filter-box").on("click",function(){
    
            $.ajax({
                        type: 'GET',
                        url: 'ajax_category_filter_data.php',
                        success: function(response) {
                            $("#category-filter-html").html(response);
                        }
                    }); 

        });

        function categorySearch(event){
                val = event.target.value;
            
                $.ajax({
                                    type: 'POST',
                                    data:{keyword:val},
                                    url: 'ajax_category_filter_data.php',
                                    success: function(response) {
                                        $("#category-filter-html").html(response);
                                    }
                                });
            }


            document.addEventListener('click', function (e) {
                                // Folder click
                                if (e.target.closest('.docCatTree .folder')) {
                                    e.stopPropagation();
                                    const folder = e.target.closest('.docCatTree .folder');

                                    // Toggle nested <ul>
                                    const ul = folder.querySelector('ul');
                                    if (ul) {
                                        ul.style.display = (ul.style.display === 'none' || ul.style.display === '') ? 'block' : 'none';
                                    }

                                    // Toggle 'active' class
                                    folder.classList.toggle('active');
                                }

                                // Content-category click
                                if (e.target.closest('.content-category')) {
                                    e.stopPropagation();
                                }
                            });


        function showFilterMaterial(id,name){

                    $.ajax({
                                type: 'POST',
                                url: 'filter_data_documents.php',
                                data:{id:id,name:name},
                                success: function(response) {
                                    $("#myModal").html();
                                    $("#myModal").html(response);
                                    $('#myModal').modal('show');
                                    $('.preloader').hide();
                                }
                            });


        }


        </script>