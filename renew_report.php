<?php include('includes/header.php');admin_page();

$dat = "";
if($_GET['f_date'] && $_GET['t_date'] )
{
	$f_dat=$_GET['f_date'];
	$t_dat=$_GET['t_date'];

    $dat .= " and DATE(created_date)>='" . $f_dat . "' and DATE(created_date)<='" . $t_dat . "'";
}
else
{
	$t_dat=date('Y-m-d');
	$f_dat=date('Y-m-d',strtotime("- 7 days"));

    $dat .= " and DATE(created_date)>='" . $f_dat . "' and DATE(created_date)<='" . $t_dat . "'";
}

if($_GET['ltype'])
{
    $f_dat=$_GET['f_date'];
    $t_dat=$_GET['t_date'];

    $dat .= " and license_type='".$_GET['ltype']."'";
}
?>

<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Stage Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Stage Report</h4>
                                </div>
                            </div>
                       
                              
								 
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                            <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                         <form method="get" name="search">
                         <div class="form-group">

                              <div>

                                        <select class="form-control" name="ltype">
                                            <option value="">Select License Type</option>
                                            <option <?= (($_GET['ltype'] == 'Commercial') ? 'selected' : '') ?> value="Commercial">Subscription New</option>
                                            <option <?= (($_GET['ltype'] == 'Renewal') ? 'selected' : '') ?> value="Renewal">Renewal</option>
                                            <option <?= (($_GET['ltype'] == 'Education') ? 'selected' : '') ?> value="Education">Education</option>
                                            <option <?= (($_GET['ltype'] == 'Student') ? 'selected' : '') ?> value="Student">Student</option>
                                            

                                        </select>
                                    </div>

                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['f_date'] ?>" class="form-control" id="f_date" name="f_date" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['t_date'] ?>" class="form-control" id="t_date" name="t_date" placeholder="Date To" />
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                        
                         </form>
                     </div> 
                                </div>
                            </div>
                               
								 <div class="table-responsive">
                                    <table id="example23" class="table display nowrap table-striped"  cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                 
                                                <th style="width:315px;">Name</th>
                                                <th style="width:90px;">Prospecting</th>
                                                <th style="width:90px;">Verification</th>
                                                <th style="width:90px;">Quote</th>
                                                <th style="width:90px;">Negotiation</th>
                                                <th style="width:74px;">Commit</th>
                                                <th style="width:98px;">EU PO Issued</th>
                                                <th style="width:92px;">Booking</th>
                                                <th style="width:74px;">OEM Billing</th>
                                                <th style="width:74px;">Closed Lost</th>
												<th style="width:50px;">Total</th>
												
												 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
										<?php $sql=db_query("select * from partners where reseller_id!='' order by partners.id desc");
										
										while($data=db_fetch_array($sql)){
										  $prospecting=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Prospecting'");
										  $verification=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Verification'");
										  $quote=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Quote'");
										  $negotiation=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Negotiation'");
										  $commit=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Commit'");
										  $eupo=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='EU PO Issued'");
										  $booking=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Booking'");
										  $oem=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='OEM Billing'");
										  $closed_lost=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' $dat and stage='Closed Lost'");
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
												<th><?=$total?></th>
                                            </tr>
										<?php } ?>
										
											</tbody>
                                    </table>
									
							   </div>
							   <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" data-page-length='25' width="100%">
						 
									<tr>
										<td style="width:330px;">Grand Total</td>
										<td style="width:90px;"><?=getSingleresult("select count(id) as leads from orders where  stage='Prospecting' $dat")?></td>
                                                <td style="width:90px;" align="center"><?=getSingleresult("select count(id) as leads from orders where     stage='Verification' $dat")?></td>
                                                <td style="width:90px;" align="center"><?=getSingleresult("select count(id) as leads from orders where     stage='Quote' $dat")?></td>
                                                <td style="width:90px;" align="center"><?=getSingleresult("select count(id) as leads from orders where     stage='Negotiation' $dat")?></td>
                                                <td style="width:74px;" align="center"><?=getSingleresult("select count(id) as leads from orders where     stage='Commit' $dat")?></td>
                                                <td style="width:98px;" align="center"><?=getSingleresult("select count(id) as leads from orders where     stage='EU PO Issued' $dat")?></td>
                                                <td style="width:92px;" align="center"><?=getSingleresult("select count(id) as leads from orders where    stage='Booking' $dat")?></td>
                                                <td style="width:74px;" align="center"><?=getSingleresult("select count(id) as leads from orders where  stage='OEM Billing' $dat")?></td>
                                                <td style="width:74px;" align="center"><?=getSingleresult("select count(id) as leads from orders where stage='Closed Lost' $dat")?></td>
												<th style="width:50px;"><?=$grand?></th>
										</tr>
									</table>
                               
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
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
function clear_search()
{
window.location='renew_report.php';
}
    </script>
    <script> 
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 340);
                $("#example23").tableHeadFixer();

            });  
	
</script>