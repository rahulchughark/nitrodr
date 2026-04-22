<?php include('includes/header.php');
 
?>

<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Reports</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Target Vs Achivement Report</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            
                             
                            <div class="">
                              	 
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
				<div class="dashboard-main-div">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
							<div class="row">
							<div class="col-lg-8">
                                <h4 class="card-title card-title-d">CDGS Visibility</h4>
                                <div class="datenotes-box"><p>Closed Date updated in "Selected Date Range"<br>
Stage "Commit + EUPO Issued + Booking + OEM Billing  + Billed to other reseller"</p>
</div>
								</div><!--col-lg-8-->
								
								<div class="col-lg-4 pull-right search_form_design">
								  <div style="float:right; transform: translate(10%, 0px);">
                         <form method="get" name="search">
                             <input type="text" value="<?php echo @$_GET['date_cdgs1']?>" class="datepicker form-control col-3" id="date_cdgs1" name="date_cdgs1" placeholder="Date" />
                             <input type="text" value="<?php echo @$_GET['date_cdgs2']?>" class="datepicker form-control col-3" id="date_cdgs2" name="date_cdgs2" placeholder="Date" />
                           
                             <input type="submit" class="btn btn-primary" value="Search" />
                             <input type="button" class="btn btn-warning" value="Clear" onclick="clear_search()" />
                         </form>
                     </div> 
					 </div><!--col-lg-4-->
					 </div><!--row-->
                               
								 <div class="table-responsive m-t-10">
                                    <table id="cdgs" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Account Name</th>
                                                <th>Lead Type</th>
                                                <th>Stage</th>
                                                <th>Caller Name</th>
                                                <th>Close Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                    <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-10">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
							<div class="row">
							<div class="col-lg-8">
                                <h4 class="card-title card-title-d">Funnel</h4>
                                <div class="datenotes-box"><p>Closed Date updated in "Selected Date Range"<br>Stage "Quote + Follow-up"</p></div>
</div><!--col-lg-8-->
								<div class="col-lg-4 search_form_design">
								  <div style="float:right; transform: translate(10%, 0px);">
                         <form method="get" name="search">
                             <input type="text" value="<?php echo @$_GET['date_funnel1']?>" class="datepicker form-control col-3" id="date_funnel1" name="date_cdgs1" placeholder="Date" />
                             <input type="text" value="<?php echo @$_GET['date_funnel2']?>" class="datepicker form-control col-3" id="date_funnel2" name="date_cdgs2" placeholder="Date" />
                           
                             <input type="submit" class="btn btn-primary" value="Search" />
                             <input type="button" class="btn btn-warning" value="Clear" onclick="clear_search()" />
                         </form>
                     </div> 
					 </div><!--col-lg-4-->
					 </div><!--row-->
                               
							   
								 <div class="table-responsive m-t-10">
                                    <table id="funnel" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Account Name</th>
                                                <th>Lead Type</th>
                                                <th>Stage</th>
                                                <th>Caller Name</th>
                                                <th>Close Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                         
                                        <tfoot>
                                    <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </tfoot> 
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-10">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
							<div class="row">
							<div class="col-lg-8">
                                <h4 class="card-title card-title-d">Billing Report</h4>
								</div><!--col-lg-8-->
								<div class="col-lg-4 search_form_design">
								  <div style="float:right; transform: translate(10%, 0px);">
                         <form method="get" name="search">
                             <input type="text" value="<?php echo @$_GET['date_qs1']?>" class="datepicker form-control col-3" id="date_qs1" name="date_qs1" placeholder="Date" />
                             <input type="text" value="<?php echo @$_GET['date_qs2']?>" class="datepicker form-control col-3" id="date_qs2" name="date_qs2" placeholder="Date" />
                           
                             <input type="submit" class="btn btn-primary" value="Search" />
                             <input type="button" class="btn btn-warning" value="Clear" onclick="clear_search()" />
                         </form>
                     </div> 
					 </div><!--col-lg-4-->
					 </div><!--row-->
					 
                               
								 <div class="table-responsive m-t-10">
                                    <table id="qtr_status" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Account Name</th>
                                                <th>Lead Type</th>
                                                <th>Stage</th>
                                                <th>Caller Name</th>
                                                <th>Close Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                         
                                        <tfoot>
                                    <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				</div><!--dashboard-main-div-->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
               
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
<div id="myModal" class="modal fade" role="dialog">
  

</div>
<?php include('includes/footer.php') ?>

<script>
        //jQuery("#search_toogle").click(function() {
          //  jQuery(".search_form").toggle("slow");
       // });

        var wfheight = $(window).height();

        $('.dashboard-main-div').height(wfheight - 250);



        $('.dashboard-main-div').slimScroll({
            color: '#00f',
            size: '10px',
            height: 'auto',


        });
    </script>
 <script>
      $(document).ready(function() {
            var dataTable = $('#cdgs').DataTable( {
                "stateSave": true,
		dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"ta_report_cdgs.php", // json datasource
                type: "post",  // method  , by default get
				data:function ( d ) {
                d.d_from = "<?=$_GET['date_cdgs1']?>";
                d.d_to = "<?=$_GET['date_cdgs2']?>";
                // d.custom = $('#myInput').val();
                // etc
					},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                }
            },
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                var json = api.ajax.json();
            // Update footer
            $(api.column(6).footer()).html(json.total);
        }, 
          
            columnDefs: [
               { orderable: false, targets: 0 }
            ],

           'columns': [
                 { data: 'id' },
                 { data: 'company_name' },
                   { data: 'lead_type' },
                   {data:'stage'},
                   {data:'caller'}, 
                   {data:'partner_close_date'},  
                   { data: 'quantity' },
                  
                  
                
              ]
        } );
            // Order by the grouping
            $('#cdgs tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
            var dataTable = $('#funnel').DataTable( {
                "stateSave": true,
		dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"ta_report_funnel.php", // json datasource
                type: "post",  // method  , by default get
				data:function ( d ) {
                d.d_from = "<?=$_GET['date_funnel1']?>";
                d.d_to = "<?=$_GET['date_funnel2']?>";
                // d.custom = $('#myInput').val();
                // etc
					},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                }
            },
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                var json = api.ajax.json();
            // Update footer
            $(api.column(6).footer()).html(json.total);
        }, 
          
            columnDefs: [
               { orderable: false, targets: 0 }
            ],

           'columns': [
                 { data: 'id' },
                 { data: 'company_name' },
                   { data: 'lead_type' },
                   {data:'stage'},
                   {data:'caller'}, 
                   {data:'partner_close_date'},  
                   { data: 'quantity' },
                  
                  
                
              ]
        } );
            // Order by the grouping
            $('#funnel tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
            var dataTable = $('#qtr_status').DataTable( {
                "stateSave": true,
		dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"ta_report_qtrstatus.php", // json datasource
                type: "post",  // method  , by default get
				data:function ( d ) {
                d.d_from = "<?=$_GET['date_qs1']?>";
                d.d_to = "<?=$_GET['date_qs2']?>";
                // d.custom = $('#myInput').val();
                // etc
					},
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                }
            },
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                var json = api.ajax.json();
            // Update footer
            $(api.column(6).footer()).html(json.total);
        }, 
          
            columnDefs: [
               { orderable: false, targets: 0 }
            ],

           'columns': [
                 { data: 'id' },
                 { data: 'company_name' },
                   { data: 'lead_type' },
                   {data:'stage'},
                   {data:'caller'}, 
                   {data:'partner_close_date'},  
                   { data: 'quantity' },
                  
                  
                
              ]
        } );
            // Order by the grouping
            $('#qtr_status tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
	
function change_goal(a,b)
{
	 $.ajax({  
    type: 'POST',  
    url: 'get_dv_data.php',
	data:{uid:a,date:b},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}	
$(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": true,
     locale: {
      format: 'YYYY-MM-DD'
    },
//startDate: '2017-01-01',
 //autoUpdateInput: false,
        
    });
});
function clear_search()
{
window.location='daily_report.php';
}
    </script>