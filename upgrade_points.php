<?php include('includes/header.php');admin_page();

$_GET['week_no'] = intval($_GET['week_no']);


function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
  }
if($_GET['d_from'] && $_GET['d_to'])
{
	$dat1=mysqli_real_escape_string($GLOBALS['dbcon'],$_GET['d_from']);
	$dat2=mysqli_real_escape_string($GLOBALS['dbcon'],$_GET['d_to']);
}
else
{
	$dat1=date('Y-m-d');
	$dat2=date('Y-m-d');
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Upgrade Rewards Points</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Upgrade Rewards Points</li>
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
								 <div style="float:right;margin-right:20px">
                         <form method="get" name="search">
                              <select name="week_no" class="form-control">
                              <option value="">---Select---</option>
                              <?php for($i=38;$i<=49;$i++)
                              { $weekarray=getStartAndEndDate($i,2019);?>
                              <option <?=(($_GET['week_no']==$i || date('W')==$i)?'selected':'')?> value="<?=$i?>">Week <?=$i?> (<?=date('d-m-Y',strtotime($weekarray['week_start']))?> - <?=date('d-m-Y',strtotime($weekarray['week_end']))?>)</option>
                              <?php } ?>
                              </select>
                             <input type="submit" value="Search" class='btn btn-primary'/>
                             <input type="button" value="Clear" onclick="clear_search()" class='btn btn-danger' />
                         </form>
                     </div> 
                                <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>
								 <div class="table-responsive m-t-40">
                                    <table id="example_z" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Rank</th>
                                                <th>Name<br><span style="font-size:8px">(Partner Name)</span></th>
                                                <th>New Account</th>
                                                <th>Account Qualified</th>
                                                <th>Customer Connect</th>
                                                <th>Quote</th>
                                                <th> Follow-Up</th>
                                                <th>Commit</th>
                                                <th> EU PO Issued</th>
                                                <th>Booking</th>
                                                <th>Billing<br><span style="font-size:8px">(Points as per No. of Seats)</span></th>
                                                <th> Net Points </th>
                                               <!--  <th> Incentive</th> -->
                                                <th>Total</th>
                                                <th>Rewards<br>Worth (in <i class="fa fa-rupee"></i>)</th>
												 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
                                           
                                        <?php 
                                        if($_GET['week_no'])
                                        {
                                            $week_check=mysqli_real_escape_string($GLOBALS['dbcon'],$_GET['week_no']);
                                        }
                                        else
                                        {
                                            $week_check=date('W');
                                        }
                                        $sql_z=db_query("select users.id,users.name,users.team_id,sum(user_points.point) as total,user_points.user_id from users left join user_points on users.id=user_points.user_id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and user_points.week_number='". $week_check."' and user_points.point!=0 GROUP by user_points.user_id order by total Desc");
										$i=1;
										while($data_z=db_fetch_array($sql_z)){
                                         $orders=db_query("select id from orders where license_type='Upgrade' and created_by=".$data_z['user_id']);
                                         while($rows=db_fetch_array($orders))
                                         {
                                             $ids[]=$rows['id'];
                                         }
                                         if(count($ids))
                                         {
                                         $idss=implode($ids);
                                         }
                                         else
                                         {
                                            $idss=0;  
                                         }
                                         $new=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=1000 and week_number=". $week_check);
                                        $approved=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and  stage_id=1001 and week_number=". $week_check);
                                        $lc=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=5 and week_number=". $week_check);
                                         $quote=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=6 and week_number=". $week_check);   
                                         $follow=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=7 and week_number=". $week_check);
                                         $commit=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=9 and week_number=". $week_check);
                                         $eupo=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=10 and week_number=". $week_check);
                                         $booking=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=11 and week_number=". $week_check);
                                         $billing=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and lead_id in (".$idss.") and stage_id=12 and week_number=". $week_check);
                                         $net=$new+$approved+$lc+$quote+$follow+$commit+$eupo+$booking+$billing;
                                         //$insentive=getSingleresult("select COALESCE(reward_points,0) from points_rewards where user_id='".$data_z['user_id']."' and week=". $week_check);
                                         $grand_total=$net+$insentive;
										?>
										
										<tr class="text-center">
                                                <td><?=$i?></td>
                                                <td class="text-left"><?=$data_z['name']?><br><span style="font-size:8px">(<?=getSingleresult("select name from partners where id=".$data_z['team_id'])?>)</span></td>
                                                <td><?=$new?></td>
                                                <td><?=$approved?></td>
                                                <td><?=$lc?></td>
                                                <td><?=$quote?></td>
                                                <td><?=$follow?></td>
                                                <td><?=$commit?></td>
                                                <td><?=$eupo?></td>
                                                <td><?=$booking?></td>
                                                <td><?=$billing;?></td>
                                                <td><?=$net?></td>
                                               <!--  <td><?=($insentive?$insentive:0)?></td> -->
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