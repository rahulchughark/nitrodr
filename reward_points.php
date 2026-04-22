<?php include('includes/header.php');admin_page();
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Rewards Points</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Rewards Points</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            
                             
                         
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
                            <div class="card-body">
                                <h4 class="card-title">Data Export</h4>
								 
                                <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>
								 <div class="table-responsive m-t-40">
                                    <table id="example_z" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th  class="text-center">Rank</th>
                                                <th class="text-center">Name<br><span style="font-size:8px">(Partner Name)</span></th>
                                                <th  class="text-center">Net Points</th>
                                                <th  class="text-center">Incentive Points</th>
                                                <th  class="text-center">Total Points</th>
                                                <th class="text-center">Rewards<br>Worth (in <i class="fa fa-rupee"></i>)</th>
												 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
                                           
                                        <?php 
                                        
                                        $sql_z=db_query("select users.id,users.name,users.team_id,sum(user_points.point) as total,user_points.user_id from users left join user_points on users.id=user_points.user_id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and user_points.point!=0 GROUP by user_points.user_id order by total Desc");
										$i=1;
										while($data_z=db_fetch_array($sql_z)){
                                            $total_points=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."'");
                                        $insentive=getSingleresult("select COALESCE(sum(reward_points),0) from points_rewards where user_id='".$data_z['user_id']."'");
                                         $grand_total=$total_points+$insentive;
										?>
										
										<tr class="text-center">
                                                <td><?=$i?></td>
                                                <td class="text-center"><?=$data_z['name']?><br><span style="font-size:8px">(<?=getSingleresult("select name from partners where id=".$data_z['team_id'])?>)</span></td>
                                               <td><?=$total_points?></td>
                                                <td><?=($insentive?$insentive:0)?></td>
                                                <td><?=$grand_total?></td>
                                                <td><?=ceil($grand_total/8)?>&nbsp;<i class="fa fa-rupee"></i></td>
												 
                                            </tr>
										<?php  $i++; }  ?>
											</tbody>
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
            var table = $('#example_z').DataTable({
				 dom: 'Bfrtip',
                 buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
				lengthMenu: [
        [ 500,1000 ],
        [ '500', '1000' ]
    ],
                "displayLength": 500,
               
            });
      
        });
   
 	
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
window.location='user_points.php';
}
$(function() {
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }
    
    $('.week-picker').datepicker( {
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            
            selectCurrentWeek();
        },
        beforeShowDay: function(date) {
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
    });
    
    $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
    $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
});
    </script>