<?php include('includes/header.php');admin_protect();
if(!$_GET['date_from'])
{
	$_GET['date_from']=date('Y-m-01');
}
if(!$_GET['date_to'])
{
	$_GET['date_to']=date('Y-m-t');
}
 

if($_POST['var_target'])
{
    
$data_save=db_query("INSERT INTO `users_targets`(`target`, `team_id`, `type`) VALUES ('".$_POST['var_target']."','".$_REQUEST['partner']."','VAR')");

if($data_save)
{
?>
<script>
alert("VAR CDGS Target saved!");
</script>

<?php }
}

if($_POST['user_target'])
{
    $data_save=db_query("INSERT INTO `users_targets`(`target`, `team_id`, `type`,`user_id`) VALUES ('".$_POST['user_target']."','".$_REQUEST['partner']."','USER','".$_POST['user_id']."')");
    if($data_save)
    {
    ?>
    <script>
    alert("User CDGS Target saved!");
    </script>
    
    <?php }
}


?>

			<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div  class="card-body ">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Target VS Acivement</small>
                                    <h4 class="font-size-14 m-0 mt-1">Target VS Acivement</h4>
                                </div>
                            </div>	
				
				<div class="clearfix"></div>
				
						 <form method="get" name="search">
                         <?php  $res=db_query("select * from partners where status='Active'");  ?>
						 <div class="row">
						 <div class="col-md-2 offset-2">
                         <select name="partner" id="partner" class="form-control ">
													 <option value="" >---Select Partner---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($_GET['partner']==$row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select>
													 </div>
													 
							<div class="col-md-2">
							
                             <input type="text" value="<?php echo @$_GET['date_from']?>" class=" form-control " id="date_from" name="date_from" placeholder="Date" />
							 </div>
							 <div class="col-md-2">
                             <input type="text" value="<?php echo @$_GET['date_to']?>" class=" form-control " id="date_to" name="date_to" placeholder="Date" />
							 </div>
							 
							 <div class="col-md-2">
                             <input type="submit" class="btn btn-primary" value="Search" />
                             <input type="button"  class="btn btn-warning" value="Clear" onclick="clear_search()" />
							 </div>
							 
							 </div>
                         </form>
				
				<!--<div class="btn-group float-right" role="group" style="margin-top:12px;">
                            <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">

                                <div class="dropdown-menu1 dropdown-menu-right filter_wrap" id="filter-container" role="menu">
									
									 <form method="get" name="search">
                         <?php  $res=db_query("select * from partners where status='Active'");  ?>
                         <select name="partner" id="partner" class="form-control ">
													 <option value="" >---Select Partner---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($_GET['partner']==$row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select>
                             <input type="text" value="<?php echo @$_GET['date_from']?>" class="datepicker form-control " id="date_from" name="date_from" placeholder="Date" />
                             <input type="text" value="<?php echo @$_GET['date_to']?>" class="datepicker form-control " id="date_to" name="date_to" placeholder="Date" />
                             <input type="submit" class="btn btn-primary" value="Search" />
                             <input type="button"  class="btn btn-warning" value="Clear" onclick="clear_search()" />
                         </form>
                    
									
									     </div>
                                </div>
                            </div>
				-->
				
             <div data-simplebar class="main_wrapper mt-2">
                            <?php if($_REQUEST['partner']) { ?> 
								 <div class="table-responsive m-t-40">
								 
                                    <table id="" class="table display nowrap table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                 
                                                <th>VAR Organization Name</th>
                                                <th class="text-center">CDGS Seats Target</th>
                                                <th class="text-center" >CDGS Seats Achieved</th>
                                                <th class="text-center">CDGS Seats Deficit</th>
                                                <th class="text-center">Target % Achieved</th>
                                                 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
										 
										
										<tr>  
                                                <td><?=getSingleresult("select name from partners where id='".$_REQUEST['partner']."'")?></td>
												 
                                               <td class="text-center"><?php $var=getSingleresult("select cdgs_target from partners where id='".$_REQUEST['partner']."'")*4;
                                                    $daily=$var/29;
                                                    
                                                    $days=dateDiffInDays($_GET['date_from'],$_GET['date_to']);
                                                     
                                                    $var= round($daily*$days,0);
                                                    
                                                   if($var)
                                                   {
                                                       echo $var;
                                                   }
                                                  ?>
                                                   
                                                
                                               </td>
                                                  <?php 
                                                  $achived=getSingleresult("select IFNULL(sum(quantity),0) from orders where status='Approved' and team_id='".$_REQUEST['partner']."' and stage in ('OEM Billing') and date(partner_close_date)>='".$_GET['date_from']."' and date(partner_close_date)<='".$_GET['date_to']."' ");
                                                  if($var) {
                                                  $percent=($achived/$var)*100;
                                                  }
                                                  else
                                                  {
                                                    $percent=0;
                                                  }
                                                  $deficit=((($var-$achived)<=0)?'0':$var-$achived);
                                                  ?>
                                               <td class="text-center"><?=($var?$achived:'Fill CDGS Seats Target')?></td>
											 
                                               <td class="text-center"><?=($var?$deficit:'Fill CDGS Seats Target')?></td>

                                               <td class="text-center"><?=($var?number_format($percent,2,'.','').'%':'Fill CDGS Seats Target')?></td>
                                           
                                            </tr>
											
									 
											</tbody>
                                    </table>
</div>
                                    
									
									<h4 class="card-title mt-2">Team Target</h4>
                                    
									<div class="table-responsive m-t-40">
                                    <table id="" class="table display nowrap table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                 
                                                <th>Member Name</th>
                                                <th class="text-center">CDGS Seats Target</th>
                                                <th class="text-center">CDGS Seats Achieved</th>
                                                <th class="text-center">CDGS Seats Deficit</th>
                                                <th class="text-center">Target % Achieved</th>
                                                 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
										 
                                        <?php $sql_users=db_query("select id,name from users where status='Active' and role!='BO' and team_id=".$_REQUEST['partner']);
                                       $i=1;
                                       while($users=db_fetch_array($sql_users))
                                        { ?>
										<tr>  
                                                <td><?=$users['name']?></td>
												 
                                               	 
                                                <td class="text-center"><?php $user_target=getSingleresult("select kra from user_kra where kra_name='1' and user_id='".$users['id']."'");
                                                   $total_user_seats+=$user_target;
                                                   ?>
                                                 <?=$user_target?>
                                                   
                                                   
                                                
                                               </td>
                                               <?php 
                                                  $achived=getSingleresult("select IFNULL(sum(quantity),0) from orders where status='Approved' and stage in ('OEM Billing') and created_by='".$users['id']."' and date(partner_close_date)>='".$_GET['date_from']."' and date(partner_close_date)<='".$_GET['date_to']."' ");
                                                  if($achived && $user_target) {
                                                  $percent=($achived/$user_target)*100;
                                                  }
                                                  else
                                                  {
                                                    $percent=0;
                                                  }
                                                  $deficit=((($user_target-$achived)<=0)?'0':$user_target-$achived);
                                                  ?>	 
                                                     <td class="text-center"><?=($user_target?$achived:'Fill CDGS Seats Target')?></td>
											 
                                             <td class="text-center"><?=($user_target?$deficit:'Fill CDGS Seats Target')?></td>

                                             <td class="text-center"><?=($user_target?number_format($percent,2,'.','').'%':'Fill CDGS Seats Target')?></td>
                                           
                                            </tr>
                                        <?php $i++; } ?>
                                        <tr>
                                            <th>
                                            Total Seats
                                            </td>
                                            <th class="text-center"><?=$total_user_seats?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            </tr>
											</tbody>
                                    </table>
                                    
									</div>
									
									<h4 class="card-title mt-2">Month wise Target Vs. Achievment</h4>
                                    <div class="table-responsive m-t-40">
                                    <table id="" class="table display nowrap table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                 
                                               
                                                <th>Billing Month</th>
                                                <th class="text-center">CDGS Seats Target</th>
                                                <th class="text-center">CDGS Seats Achived</th>
                                                <th class="text-center">CDGS Seats Deficit</th>
                                                <th class="text-center">Target % Achieved</th>
                                                 
                                            </tr>
                                            
                                        </thead>
                                         
                                        <tbody>
										 
                                        <?php  for($i=1;$i<=12;$i++)
                                        {  $month=date("m", mktime(0, 0, 0, $i, 10))?>
										<tr>  
                                            
                                                <td><?=date("F", mktime(0, 0, 0, $i, 10)); ?></td>
                                                <td class="text-center"><?=($var?$var:'Fill CDGS Seats Target')?></td>

                                                <?php 
                                                  $achived_month=getSingleresult("select IFNULL(sum(quantity),0) from orders where status='Approved' and team_id='".$_REQUEST['partner']."' and stage in ('OEM Billing') and month(partner_close_date)='".$month."' and year(partner_close_date)='".date('Y')."'");
                                                 $achived+=$achived_month;

                                                  if($var && $achived_month) {
                                                  $percent=($achived_month/$var)*100;
                                                  }
                                                  else
                                                  {
                                                    $percent=0;
                                                  }
                                                  $deficit=((($var-$achived_month)<=0)?'0':$var-$achived_month);
                                                  $total_achived+=$achived_month;
                                                  $def+=$var-$achived_month;

                                                  ?>	 

                                                <td class="text-center"><?=$achived_month?></td>
                                                <td class="text-center"><?=($var?$deficit:'Fill CDGS Seats Target')?></td>

                                             <td class="text-center"><?=($var?number_format($percent,2,'.','').'%':'Fill CDGS Seats Target')?></td>
                                           
                                            </tr>
                                        <?php } ?>
                                        <tr>  
                                                <th>Total</th>
												 
                                               	 
                                                
												 	 <?php $achived_percent=($achived/($var*12))*100; ?>
                                                <th class="text-center"><?=$var*12?></th>
											 
                                                <th class="text-center"><?=$achived?></th>
                                                 	 
                                               <th class="text-center"><?=$def?></th>
                                               <th class="text-center"><?=number_format($achived_percent,2,'.','')?>%</th>
                                           
                                           
                                            </tr>
											</tbody>
                                    </table>
                                                <?php } ?>
                                </div>
								
								</div>
								
								
                            </div>
                        </div>
                    
					
		</div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
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
				lengthMenu: [
        [ 10, 25, 50, 100,500,1000 ],
        [ '10', '25', '50','100','500', '1000' ]
    ],
                "displayLength": 25,
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

function validate_seats(id,total)
{

    //var team_size=$(":input[name='user_target']").length;
    var sum=0;
    $(":input[name='user_target']").each(function() {
        sum += Number($(this).val());
    });
  
    if(sum<total)
    {
        document.getElementById('form_'+id).submit();
    }
    else
    {
        alert('Team target should be equal to VAR CDGS Target');
    }
    

}
    </script>
	    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.main_wrapper').height(wfheight - 230);
        });
    </script>