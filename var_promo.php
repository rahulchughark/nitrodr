<?php include('includes/header.php'); admin_page();
ini_set('max_execution_time', '0');
if($_GET['date'])
{
	$dat=$_GET['date'];
}
else
{
	$dat=date('Y-m-d');
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

                                    <small class="text-muted">Home >VAR Promo</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Promo</h4>
                                </div>
                            </div>
<br>
                                
                                <table class="table table-hover table-striped table-bordered" class width="102" cellspacing="1" cellpadding="1"><caption>&nbsp;</caption>
<tbody>
<tr>
<th style="width: 62.4px;">VAR Promo Month</th>
<th style="width: 67.2px;">Approved DR Point</th>
<th style="width: 60.8px;">Rejected DR Point</th>
</tr>
<tr>
<th style="width: 62.4px;">January</th>
<td style="width: 67.2px;">&nbsp;2</td>
<td style="width: 60.8px;">-1.5</td>
</tr>
<tr>
<th style="width: 62.4px;">Feburary</th>
<td style="width: 67.2px;">&nbsp;1.5</td>
<td style="width: 60.8px;">-1</td>
</tr>
<tr>
<th style="width: 62.4px;">March</th>
<td style="width: 67.2px;">&nbsp;1.5</td>
<td style="width: 60.8px;">-1</td>
</tr>
</tbody>
</table> 
								 <div class="table-responsive m-t-10">
                                    
                                    
                                   
                                   
                                    <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                     <tr class="text-center">
                                    <th rowspan="2">Rank</th>
                                    <th rowspan="2">Partner Name</th>
                                    <th class="text-center" style="color:blue;border:2px solid blue" colspan="4">January Points</th>
                                    <th class="text-center" style="color:orange;border:2px solid orange" colspan="4">February Points</th>
                                    <th class="text-center" style="color:brown;border:2px solid orange" colspan="4">March Points</th>
                                    <th rowspan="2" class="text-center">Total J&F Point earned</th>
                                </tr>
                                            <tr>
                                                <th style="color:green;border:2px solid green" class="text-center">Approved Seats</th>
                                                <th style="color:green;border:2px solid green" class="text-center">Approved DR</th>
                                                <th style="color:red;border:2px solid red"  class="text-center">Rejected Seats</th>
                                                <th style="color:red;border:2px solid red" class="text-center">Rejected DR</th>
                                                <th style="color:green;border:2px solid green" class="text-center">Approved Seats</th>
                                                <th style="color:green;border:2px solid green" class="text-center">Approved DR</th>
                                                <th style="color:red;border:2px solid red" class="text-center">Rejected Seats</th>
                                                <th style="color:red;border:2px solid red" class="text-center">Rejected DR</th>
                                                <th style="color:green;border:2px solid green" class="text-center">Approved Seats</th>
                                                <th style="color:green;border:2px solid green" class="text-center">Approved DR</th>
                                                <th style="color:red;border:2px solid red" class="text-center">Rejected Seats</th>
                                                <th style="color:red;border:2px solid red" class="text-center">Rejected DR</th>
                                               
                                               
												 
                                            </tr>
                                        </thead>
                                    <tbody>
                                    <?php  $i=1; //$query=db_query("select partners.id,partners.name,sum(var_promo.point) as points from partners left join var_promo on partners.id=var_promo.team_id  where 1 and partners.status='Active' group by partners.id order by points desc ");
                                        $query=db_query("select partners.id,partners.name,(select SUM(orders.quantity) as quan from orders where orders.partner_close_date>='2020-01-01' and orders.partner_close_date<='2020-03-31' and orders.stage='OEM Billing' and team_id=partners.id) as quantity from partners WHERE status='Active' order by quantity desc");
                                    while($data=db_fetch_array($query))
                                    {
                                        
                                        $jan_approved=getSingleresult("SELECT IFNULL(sum(quantity),0) from orders WHERE stage='OEM Billing' and status='Approved' and partner_close_date>='2020-01-01' and partner_close_date<='2020-01-31' and license_type='Commercial' and team_id='".$data['id']."'");
                                        $jan_cancelled=getSingleresult("SELECT IFNULL(sum(quantity),0) from orders WHERE stage='OEM Billing' and status='Cancelled' and partner_close_date>='2020-01-01' and partner_close_date<='2020-01-31' and license_type='Commercial' and team_id='".$data['id']."'");
                                        $feb_approved=getSingleresult("SELECT IFNULL(sum(quantity),0) from orders WHERE stage='OEM Billing' and status='Approved' and partner_close_date>='2020-02-01' and partner_close_date<='2020-02-29' and license_type='Commercial' and team_id='".$data['id']."'");
                                        $feb_cancelled=getSingleresult("SELECT IFNULL(sum(quantity),0) from orders WHERE stage='OEM Billing' and status='Cancelled' and partner_close_date>='2020-02-01' and partner_close_date<='2020-02-29' and license_type='Commercial' and team_id='".$data['id']."'");
                                        $mar_approved=getSingleresult("SELECT IFNULL(sum(quantity),0) from orders WHERE stage='OEM Billing' and status='Approved' and partner_close_date>='2020-03-01' and partner_close_date<='2020-03-31' and license_type='Commercial' and team_id='".$data['id']."'");
                                        $mar_cancelled=getSingleresult("SELECT IFNULL(sum(quantity),0) from orders WHERE stage='OEM Billing' and status='Cancelled' and partner_close_date>='2020-03-01' and partner_close_date<='2020-03-31' and license_type='Commercial' and team_id='".$data['id']."'");

                                        $jan_app_points=$jan_approved*2;
                                        if($jan_cancelled)
                                        $jan_can_points=$jan_cancelled*(-1.5);
                                        else
                                        $jan_can_points=0;

                                        $feb_app_points=$feb_approved*1.5;
                                        if($feb_cancelled)
                                        $feb_can_points=$feb_cancelled*(-1);
                                        else
                                        $feb_can_points=0;

                                        $mar_app_points=$mar_approved*1.5;
                                        if($mar_cancelled)
                                        $mar_can_points=$mar_cancelled*(-1);
                                        else
                                        $mar_can_points=0;

                                        $data['points']=$jan_app_points+$jan_can_points+$feb_app_points+$feb_can_points+$mar_app_points+$mar_can_points;
                                        
                                        ?>
                                    <tr>
                                    <td ><strong><?=$i?></strong></td>
                                    <td ><?=$data['name']?>   </td>
                                    <td class="text-center" ><?=($jan_approved?$jan_approved:0)?></td>
                                    <td class="text-center"><?=$jan_app_points?></td>
                                    <td class="text-center" ><?=($jan_cancelled?$jan_cancelled:0)?></td>
                                    <td class="text-center"><?=$jan_can_points?></td>
                                    <td class="text-center" ><?=($feb_approved?$feb_approved:0)?></td>
                                    <td class="text-center"><?=$feb_app_points?></td>
                                    <td class="text-center" ><?=($feb_cancelled?$feb_cancelled:0)?></td>
                                    <td class="text-center"><?=$feb_can_points?></td>

                                    <td class="text-center" ><?=($mar_approved?$mar_approved:0)?></td>
                                    <td class="text-center"><?=$mar_app_points?></td>
                                    <td class="text-center" ><?=($mar_cancelled?$mar_cancelled:0)?></td>
                                    <td class="text-center"><?=$mar_can_points?></td>
                                    <td class="text-center"><strong><span class="text-grey"><?=($data['points']?$data['points']:'0')?></strong></span></td>
                                </tr>
                                     

                                    <?php $i++; } ?>
                                    </tbody>
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
<?php include('includes/footer.php') ?>
 <script>
     $(function () {
        $('.tooltip-demo.well').tooltip({
  selector: "a[rel=tooltip]"
});
});
    $(document).ready(function() {
       
        $('#myTable').DataTable();
        $(document).ready(function() {
            
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
        "displayLength": 500,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
        columnDefs: [
               { orderable: false}
            ],
            lengthMenu: [
        [ 500,1000 ],
        [ '500', '1000' ]
    ],
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
window.location='var_promo.php';
}
    </script>
      <script> 
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            }); 
	
</script>