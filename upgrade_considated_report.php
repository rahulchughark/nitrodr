<?php include('includes/header.php');admin_page();
if($_GET['f_date'] && $_GET['t_date'] )
{
	$f_dat=$_GET['f_date'];
	$t_dat=$_GET['t_date'];
}
else
{
	$t_dat=date('Y-m-d');
	$f_dat=date('Y-m-d',strtotime("- 7 days"));
}
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Upgrade Report</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Upgrade Report</li>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body fixed-table-body">
                                <h4 class="card-title">Data Export</h4>
								  <div style="float:right;margin-right:20px">
                         <form method="get" name="search">
                             <input type="text" value="<?php echo @$_GET['f_date']?>" class="datepicker" id="f_date" name="f_date" placeholder="Date" />
							  <input type="text" value="<?php echo @$_GET['t_date']?>" class="datepicker " id="t_date" name="t_date" placeholder="Date" />
                            
                             <input type="submit" value="Search" class="btn btn-success" />
                             <input type="button" value="Clear" onclick="clear_search()" class="btn btn-warning" />
                         </form>
                     </div> 
                                <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>
								 <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" data-page-length='25' width="100%">
                                        <thead>
                                            <tr>
                                               <th>Name</th>
                                                <th>Prospecting</th>
                                                <th>Verification</th>
                                                <th>Quote</th>
                                                <th>Negot-<br>iation</th>
                                                <th>Commit</th>
                                                <th>EU PO Issued</th>
                                                <th>Booking</th>
                                                <th>OEM Billing</th>
                                                <th>Closed Lost</th>
												<th>Total</th>
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
										<?php $sql=db_query("select * from partners where reseller_id!='' order by partners.id desc");
										
										while($data=db_fetch_array($sql)){
										  $prospecting=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Prospecting'");
										  $verification=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Verification'");
										  $quote=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Quote'");
										  $negotiation=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Negotiation'");
										  $commit=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Commit'");
										  $eupo=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='EU PO Issued'");
										  $booking=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Booking'");
										  $oem=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='OEM Billing'");
										  $closed_lost=getSingleresult("select count(id) as leads from upgrade_leads where reseller='".$data['id']."' and stage='Closed Lost'");
										  $total=$prospecting+$verification+$quote+$negotiation+$commit+$eupo+$booking+$oem+$closed_lost;
										  $grand+=$total;
										?>
										
										<tr>
                                                    <td><?=$data['2']?></td>
                                                 <td><?=$prospecting?></td>
                                                <td><?=$verification?></td>
                                                <td><?=$quote?></td>
                                                <td><?=$negotiation?></td>
                                                <td><?=$commit?></td>
                                                <td><?=$eupo?></td>
                                                <td><?=$booking?></td>
                                                <td><?=$oem?></td>
                                                <td><?=$closed_lost?></td>
												<td><?=$total?></td>
                                                 
                                                 
                                                 
                                            </tr>
										<?php } ?>
										
											</tbody>
                                    </table>
									<table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" data-page-length='25' width="100%">
						 
									<tr>
										<td style="width:295px;">Grand Total</td>
										<td style="width:127px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where  stage='Prospecting'")?></td>
                                                <td style="width:119px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where     stage='Verification'")?></td>
                                                <td style="width:67px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where     stage='Quote'")?></td>
                                                <td style="width:69px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where     stage='Negotiation'")?></td>
                                                <td style="width:82px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where     stage='Commit'")?></td>
                                                <td style="width:130px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where     stage='EU PO Issued'")?></td>
                                                <td style="width:112px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where    stage='Booking'")?></td>
                                                <td style="width:112px;"><?=getSingleresult("select count(id) as leads from upgrade_leads where  stage='OEM Billing'")?></td>
                                                <td style="width:145px;" ><?=getSingleresult("select count(id) as leads from upgrade_leads where stage='Closed Lost'")?></td>
												<th style="width:50px;"><?=$grand?></th>
										</tr>
									</table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'desc']
                ],
				 buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
				 
                "pageLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ]
    });
	
function change_goal(a)
{
	 $.ajax({  
    type: 'POST',  
    url: 'get_goal.php',
	data:{pid:a},
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
window.location='upgrade_considated_report.php';
}
    </script>
     <script> 
 jQuery("#search_toogle").click(function(){
    jQuery(".search_form").toggle("fast");
});

  var wfheight = $(window).height();
                  
                  $('.fixed-table-body').height(wfheight-195);
                  


      $('.fixed-table-body').slimScroll({
        color: '#00f',
        size: '10px',
       height: 'auto',
	   
      
    });  
	
</script>