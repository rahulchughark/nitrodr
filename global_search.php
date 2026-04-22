<?php include('includes/header.php');
$requestData = $_REQUEST;
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
                                    <small class="text-muted">Home >Global Search</small>
                                    <h4 class="font-size-14 m-0 mt-1">Global Search</h4>
                                </div>
                            </div>
                            
							<div class="clearfix"></div>							
							<div data-simplebar class="global_search_wrap">

                            <?php if($_SESSION['user_type']!='RENEWAL TL'&& $_SESSION['user_type']!='RM'){ ?>    
                              <h5 class="card-title">Leads</h5>
                            <div class="table-responsive" id="MyDiv">
                           
                                <table id="leads" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th data-sortable="true">Reseller Name</th>
                                            <th data-sortable="true">School Board</th>
                                            <th data-sortable="true">School Name</th>
                                            <th data-sortable="true">Date of Submission</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Qualified Status</th>
                                            <th data-sortable="true">Stage</th>
                                            <th data-sortable="true">Sub Stage</th>
                                            <th data-sortable="true">Tag</th>
                                            <th data-sortable="true">Closed Date</th>
                                            <th data-sortable="true">Alligned To</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <?php } ?>

                            <h5 class="card-title">Renewal</h5>
                            <div class="table-responsive">
                                <table id="renewal" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>S.No.</th>
                                        <th>Reseller Name</th>
                                        <th>School Board</th>
                                        <th>School Name</th>
                                        <th style="min-width: 300px">Product</th>
                                        <th>Quantity</th>
                                        <th>Grand Total</th>
                                        <th>Date of Submission</th>
                                        <th>Status</th>
                                        <th>Qualified Status</th>
                                        <th>Stage</th>
                                        <th>Sub Stage</th>
                                        <th>Closed Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                           <?php if($_SESSION['user_type']!='RENEWAL TL'&& $_SESSION['user_type']!='RM'){ ?>
                            <h5 class="card-title">Opportunity</h5>
                            <div class="table-responsive" id="MyDiv">
                           
                                <table id="opportunity" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>S.No.</th>
                                        <th>Reseller Name</th>
                                        <th>School Board</th>
                                        <th>School Name</th>
                                        <th style="min-width: 300px">Product</th>
                                        <th>Quantity</th>
                                        <th>Grand Total</th>
                                        <th>Date of Submission</th>
                                        <th>Status</th>
                                        <th>Qualified Status</th>
                                        <th>Stage</th>
                                        <th>Sub Stage</th>
                                        <th>Closed Date</th>                                           
                                        </tr>
                                    </thead>
                                </table>
                            </div> 
                           <?php } ?>


                        </div>
						
						</div>
                    </div>


                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <div id="myModal1" class="modal" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
     
        <script>
              $(document).ready(function() {
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: ['pageLength' ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "searching": false,

                "ajax": {
                    url: "global_search_ajax.php?type=leads", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.search = "<?= $requestData['search'] ?>";
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [         
                    { data: 'id' },
                    { data: 'r_name' },
                    {data:'school_board'},
                    { data: 'school_name' },
                    {data:'created_date'},
                    {data:'status'},
                    {data:'qualified_status'},
                    {data:'stage'},
                    {data:'sub_stage'},
                    {data:'tag'},
                    {data:'close_date'},
                    {data:'allign_to'},
                ],
                "oLanguage": {
                    "sProcessing": "<span class='search'>Searching...</span>"
                }
            });
        });
        </script>



        <script>
             $(document).ready(function() {
            $('#renewal').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: ['pageLength' ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "searching": false,

                "ajax": {
                    url: "global_search_ajax.php?type=renewal", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.search = "<?= $requestData['search'] ?>";


                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#renewal").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },


                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                    { data: 'id' },
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'product' },
                 { data: 'quantity' },
                 { data: 'grand_total' },
                   {data:'created_date'},
                   {data:'status'},
                   {data:'qualified_status'},
                  {data:'stage'},
                  {data:'sub_stage'},
                  {data:'close_date'},


                ],
                "oLanguage": {
                    "sProcessing": "<span class='search'>Searching...</span>"
                }
            });
        });
        </script>

        <script>
               $(document).ready(function() {
            $('#opportunity').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: ['pageLength' ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "searching": false,

                "ajax": {
                    url: "global_search_ajax.php?type=opportunity", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.search = "<?= $requestData['search'] ?>";

                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                { data: 'id' }, 
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'product' },
                 { data: 'quantity' },
                 { data: 'grand_total' },
                   {data:'created_date'},
                  {data:'status'},
                  {data:'qualified_status'},
                  {data:'stage'},
                  {data:'sub_stage'},
                  {data:'close_date'},
                ],
                "oLanguage": {
                    "sProcessing": "<span class='search'>Searching...</span>"
                }
            });
        });
        </script>

		<script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.global_search_wrap').height(wfheight - 190);
       
    
    
});



    </script>
 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
            function status_change(ids, id) {
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'status_change.php',
                    data: {
                        pid: id,
                        ids: ids,
                        from:'global'
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function stage_change(ids, id) {
                //$('.preloader').show();
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'stage_change.php',
                    data: {
                        pid: id,
                        ids: ids,
                        page_access: page_access
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id, ids, substage, payment_status, attachments,demo_datetime) {

if (stage != '') {
    var formData = new FormData();
    formData.append('stage', stage);
    formData.append('substage', substage);
    formData.append('lead_id', id);
    formData.append('payment_status', payment_status);
    formData.append('demo_datetime', demo_datetime);

    // Append files to FormData
    for (var i = 0; i < attachments.length; i++) {
        formData.append('attachments[]', attachments[i]);
    }

    $('#myModal1').modal('hide');
    $.ajax({
        type: 'post',
        url: 'change_stage.php',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            var res = $.trim(res);
            if (res == 'success') {
                swal({
                    title: "Done!",
                    text: "Stage changed Successfully.",
                    type: "success"
                }, function() {
                    $('#myModal1').modal('hide');
                    var idss = "'but" + id + "'";
                    var link = stage + '<a href="javascript:void(0)" title="Change Stage" id=but' + id + ' onclick="stage_change(' + idss + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                    $("#" + ids).parent().html(link);
                    $('#leads').DataTable().ajax.reload();
                });

            } else {
                swal({
                    title: "Error!",
                    text: res,
                    type: "error"
                }, function() {

                });
            }
        }
    });
}
}
</script>