<?php include("includes/include.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Corel | DR Portal</title>
    <!-- Bootstrap Core CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<link href="assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
    <!--link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet"-->
    
    <!-- chartist CSS -->
    <link href="assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.css" rel="stylesheet">
    <link href="assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
	   <link href="assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
	    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.skinModern.css" rel="stylesheet">
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!--This page css - Morris CSS -->
    <link href="assets/plugins/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">

    <div class="row">
                <div class="col-lg-12 col-md-4">
                        <div class="card">
                            <img class="" src="assets/images/background/user-info.jpg" alt="Card image cap">
                            <div class="card-img-overlay text-center" style="height:110px;">
                                <h1 class="card-title text-white m-b-0 dl">Corel Rewards Program</h1>
                                <br>
                                <h4 class="card-text text-white font-light">Rank across all participants</h4>
                              </div>
                            <div class="card-body weather-small">
                                <div class="row">
                                    <div class="col-12 b-r align-self-center">
                                        <div class="d-flex">
                                        <div class="table-responsive m-t-40">
                                    <table id="example_z" class="display nowrap table table-hover table-striped table-bordered center" cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="text-center">Rank</th>
                                                <th class="text-center">VAR Name</th>
                                                <th class="text-center">User Name</th>
                                                <th class="text-center">Activity Points </th>
                                                <th class="text-center">Weekly Incentive Points</th>
                                                <th class="text-center">Total Points</th>
                                                
												 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
                                           
                                        <?php 
                                        if($_GET['week_no'])
                                        {
                                            $week_check=base64_decode($_GET['week_no']);
                                        }
                                        else
                                        {
                                            $week_check=date('W');
                                        }
                                        $sql_z=db_query("select users.id,users.name,users.team_id,sum(user_points.point) as total,user_points.user_id from users left join user_points on users.id=user_points.user_id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and user_points.week_number='". $week_check."' and user_points.point!=0 GROUP by user_points.user_id order by total Desc");
										$i=1;
										while($data_z=db_fetch_array($sql_z)){
                                         $new=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=1000 and week_number=". $week_check);
                                        $approved=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=1001 and week_number=". $week_check);
                                        $lc=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=5 and week_number=". $week_check);
                                         $quote=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=6 and week_number=". $week_check);   
                                         $follow=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=7 and week_number=". $week_check);
                                         $commit=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=9 and week_number=". $week_check);
                                         $eupo=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=10 and week_number=". $week_check);
                                         $booking=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=11 and week_number=". $week_check);
                                         $billing=getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='".$data_z['user_id']."' and stage_id=12 and week_number=". $week_check);
                                         $net=$new+$approved+$lc+$quote+$follow+$commit+$eupo+$booking+$billing;
                                         $insentive=getSingleresult("select COALESCE(reward_points,0) from points_rewards where user_id='".$data_z['user_id']."' and week=". $week_check);
                                         $grand_total=$net+$insentive;
										?>
										
										<tr class="text-center">
                                                <td <?php if($i<=10) { ?> style="font-weight:900" <?php } ?>><?=$i?></td>
                                                <td <?php if($i<=10) { ?> style="font-weight:900" <?php } ?> class="text-left"><?=getSingleresult("select name from partners where id=".$data_z['team_id'])?></td>
                                                <td <?php if($i<=10) { ?> style="font-weight:900" <?php } ?> class="text-left"><?=$data_z['name']?></td>
                                               
                                                <td <?php if($i<=10) { ?> style="font-weight:900" <?php } ?> class="text-center"><?=$net?></td>
                                                <td <?php if($i<=10) { ?> style="font-weight:900" <?php } ?> class="text-center"><?=($insentive?$insentive:0)?></td>
                                                <td <?php if($i<=10) { ?> style="font-weight:900" <?php } ?> class="text-center"><?=$grand_total?></td>
                                               
												 
                                            </tr>
										<?php  $i++; }  ?>
											</tbody>
                                    </table>
									 
                                </div>
                                        
                                    </div>
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

    <?php include("includes/footer.php");?>

    <style>
    .footer{
        left:0 !important;
    }
    .text-right {
    text-align: center !important;
}
    </style>
    
    <script>
    
    
    $(document).ready(function() {
      var table = $('#example_z').DataTable({
           dom: 'Bfrtip',
           buttons: [
      
  ],
          lengthMenu: [
  [ 500,1000 ],
  [ '500', '1000' ]
],
          "displayLength": 500,
         
      });

  });
    </script>