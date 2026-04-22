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
    
$data_save=db_query("INSERT INTO `users_targets`(`target`, `team_id`, `type`) VALUES ('".$_POST['var_target']."','".$_SESSION['team_id']."','VAR')");

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
    $data_save=db_query("INSERT INTO `users_targets`(`target`, `team_id`, `type`,`user_id`) VALUES ('".$_POST['user_target']."','".$_SESSION['team_id']."','USER','".$_POST['user_id']."')");
    if($data_save)
    {
    ?>
    <script>
    alert("User CDGS Target saved!");
    </script>
    
    <?php }
}


?>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid"> 



            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                         <div class="media ">
                            <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                            <div class="media-body">

                                <small class="text-muted">Home >Target VS Acivement</small>
                                <h4 class="font-size-14 m-0 mt-1">Target VS Acivement</h4>
                            </div>
                        </div>

                            <div class="table-responsive">
                           <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">
                                    
                                                <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="form-control" id="date_from" name="date_from" placeholder="Date From" autocomplete="off"/>
                                                
                                                    <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="form-control" id="date_to" name="date_to" placeholder="Date To" autocomplete="off"/>
                                                </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                            </form>
                                        </div>
                                    </div>

                                </div>


                                <h4 class="card-title card-title-d">Organization Target</h4>
                                <table id="" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                           <tr> 
                                                <th data-sortable="true">VAR Organization Name</th>
                                                <th data-sortable="true">CDGS Seats Target</th>
                                                <th data-sortable="true">CDGS Seats Achieved</th>
                                                <th data-sortable="true">CDGS Seats Deficit</th>
                                                <th data-sortable="true">Target % Achieved</th>
                                                 
                                            </tr>

                                    </thead>

                                      <tbody>
                                            <tr>  
                                                <td><?=getSingleresult("select name from partners where id=".$_SESSION['team_id'])?></td>
                         
                                               <td ><?php $var=getSingleresult("select cdgs_target from partners where id=".$_SESSION['team_id'])*4;
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
                                                  $achived=getSingleresult("select IFNULL(sum(quantity),0) from orders where status='Approved' and team_id='".$_SESSION['team_id']."' and stage in ('OEM Billing') and date(partner_close_date)>='".$_GET['date_from']."' and date(partner_close_date)<='".$_GET['date_to']."' ");
                                                  if($var) {
                                                  $percent=($achived/$var)*100;
                                                  }
                                                  else
                                                  {
                                                    $percent=0;
                                                  }
                                                  $deficit=((($var-$achived)<=0)?'0':$var-$achived);
                                                  ?>
                                               <td ><?=($var?$achived:'Fill CDGS Seats Target')?></td>
                       
                                               <td ><?=($var?$deficit:'Fill CDGS Seats Target')?></td>

                                               <td ><?=($var?number_format($percent,2,'.','').'%':'Fill CDGS Seats Target')?></td>
                                           
                                            </tr>
                                        </tbody>
                                </table>
                                <h4 class="card-title card-title-d">Team Target</h4>
                            <table id="" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                           <tr>
                                               <th data-sortable="true">Member Name</th>
                                                <th data-sortable="true">CDGS Seats Target</th>
                                                <th data-sortable="true">CDGS Seats Achieved</th>
                                                <th data-sortable="true">CDGS Seats Deficit</th>
                                                <th data-sortable="true">Target % Achieved</th>

                                            </tr>

                                    </thead>

                                      <tbody>
                                           <?php $sql_users=db_query("select id,name from users where status='Active' and role!='BO' and team_id=".$_SESSION['team_id']);
                                       $i=1;
                                       while($users=db_fetch_array($sql_users))
                                        { ?>
                                              <tr>  
                                                <td><?=$users['name']?></td>
                         
                                                 
                                                <td ><?php $user_target=getSingleresult("select kra from user_kra where kra_name='1' and user_id='".$users['id']."'");
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
                                                     <td ><?=($user_target?$achived:'Fill CDGS Seats Target')?></td>
                       
                                             <td ><?=($user_target?$deficit:'Fill CDGS Seats Target')?></td>

                                             <td ><?=($user_target?number_format($percent,2,'.','').'%':'Fill CDGS Seats Target')?></td>
                                           
                                            </tr>
                                        <?php $i++; } ?>
                                        <tr>
                                            <th>
                                            Total Seats
                                            </td>
                                            <th ><?=$total_user_seats?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            </tr>
                                        </tbody>
                                </table>

                                <h4 class="card-title card-title-d">Month wise Target Vs. Achievment</h4>
                            <table id="" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                           <tr> 
                                                <th data-sortable="true">Billing Month</th>
                                                <th data-sortable="true">CDGS Seats Target</th>
                                                <th data-sortable="true">CDGS Seats Achived</th>
                                                <th data-sortable="true">CDGS Seats Deficit</th>
                                                <th data-sortable="true">Target % Achieved</th>
                                                 
                                            </tr>
                                           
                                    </thead>

                                      <tbody>
                                           <?php  for($i=1;$i<=12;$i++)
                                        {  $month=date("m", mktime(0, 0, 0, $i, 10))?>
                                          <tr>  
                                            
                                                <td><?=date("F", mktime(0, 0, 0, $i, 10)); ?></td>
                                                <td ><?=($var?$var:'Fill CDGS Seats Target')?></td>

                                                <?php 
                                                  $achived_month=getSingleresult("select IFNULL(sum(quantity),0) from orders where status='Approved' and team_id='".$_SESSION['team_id']."' and stage in ('OEM Billing') and month(partner_close_date)='".$month."' and year(partner_close_date)='".date('Y')."'");
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

                                                <td ><?=$achived_month?></td>
                                                <td ><?=($var?$deficit:'Fill CDGS Seats Target')?></td>

                                             <td ><?=($var?number_format($percent,2,'.','').'%':'Fill CDGS Seats Target')?></td>
                                           
                                            </tr>
                                        <?php } ?>
                                        <tr>  
                                                <th>Total</th>
                         
                                                 
                                                
                           <?php $achived_percent=($achived/($var*12))*100; ?>
                                                <th ><?=$var*12?></th>
                       
                                                <th ><?=$achived?></th>
                                                   
                                               <th ><?=$def?></th>
                                               <th ><?=number_format($achived_percent,2,'.','')?>%</th>
                                           
                                           
                                            </tr>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

</div>


<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<div id="myModal1" class="modal" role="dialog">


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
    $('#datepicker-close-date').datepicker({
       format: 'yyyy-mm-dd',
       //startDate: '-3d',
       autoclose:!0

    });
    
});

function clear_search()
{
window.location='target_achivement.php';
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
        jQuery("#search_toogle").click(function() {
            jQuery(".search_form").toggle("slow");
        });

        var wfheight = $(window).height();

        $('.card-body').height(wfheight - 205);



        $('.card-body').slimScroll({
            color: '#00f',
            size: '10px',
            height: 'auto',


        });
    </script>