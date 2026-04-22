<?php include('includes/header.php'); admin_protect();
//$_GET['f_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_GET['f_date']);
//$_GET['t_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_GET['t_date']);


if ($_GET['f_date'] && $_GET['t_date']) {
    $date_from = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($_GET['f_date']));
    $date_to = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($_GET['t_date']));
} else {
    $date_to = date('Y-m-d');
    $date_from = date('Y-m-d', strtotime("- 7 days"));
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

                                    <small class="text-muted">Home >Reports</small>
                                    <h4 class="font-size-14 m-0 mt-1">DR Action Report</h4>
                                </div>
                            </div>

                            
                    <div class="clearfix"></div>           
                            <div class="btn-group float-right" role="group" style="margin-top:-35px;">
                            <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


<div class="dropdown dropdown-lg">

    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                  <form method="get" name="search" class="form-horizontal" role="form">

                                    <div class="form-group">
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
                     
                            <h5 class="card-subtitle">DR Action Timespan - Admin</h5>
                                <h6 class="card-subtitle">Showing data from <?=date('d-m-Y',strtotime($date_from))?> to <?=date('d-m-Y',strtotime($date_to))?></h6>
								 <div class="table-responsive">
                                    <table id="" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">S.No.</th>
                                                <th width="50%" class="text-center">Lead Type</th>
                                                <th class="text-center">Timespan in Hours</th>                                                
                                            </tr>
                                        </thead>
                                         
                                        <tbody>	
                                        <?php 
                                        //FOr LC
                                        $lc_query=db_query("select id,created_date,orders.approval_time,convert_date FROM orders where status='Approved' and lead_type='LC' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
                                        $total_time=0;
                                        $count_lc=db_num_array($lc_query);
                                        if($count_lc){
                                        while($lc_data=db_fetch_array($lc_query))
                                        {

                                            if($lc_data['convert_date'])
                                            {
                                                $time_diff=diffhours($lc_data['convert_date'],$lc_data['approval_time']);
                                            }
                                            else
                                            {
                                                $time_diff+=diffhours($lc_data['created_date'],$lc_data['approval_time']);  
                                                //print_r($time_diff);
                                            }
                                            if($time_diff<200)
                                            {
                                                $total_time+=$time_diff;
                                            }
                                        }
                                       
                                        $lc=$total_time/$count_lc;
                                        //print_r($lc);
                                        }

                                        $bd_query=db_query("select id,created_date,orders.approval_time,convert_date FROM orders where status='Approved' and lead_type='BD' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
                                        $total_time=0;
                                        $count_bd=db_num_array($bd_query);
                                        if($count_bd){
                                        while($bd_data=db_fetch_array($bd_query))
                                        {
                                            $starttimestamp = strtotime($bd_data['created_date']);                                            
                                            $endtimestamp = strtotime($bd_data['approval_time']);                                            
                                            $difference = abs($endtimestamp - $starttimestamp)/3600;
                                            
                                            if($bd_data['convert_date'])
                                            {
                                                $time_diff=diffhours($bd_data['convert_date'],$bd_data['approval_time']);
                                                //print_r($time_diff);
                                            }
                                            else
                                            {
                                                $time_diff+=diffhours($bd_data['created_date'],$bd_data['approval_time']); 
                                                //print_r($time_diff); 
                                            }
                                            if($time_diff<200)
                                            {
                                                $total_time+=$time_diff;
                                            }
                                        }
                                        $bd=$total_time/$count_bd;
                                        
                                        //print_r($count_bd);
                                        //print_r($bd);
                                        }
                                       
                                        $in_query=db_query("select id,created_date,orders.approval_time,convert_date,IFNULL(AVG(TIMESTAMPDIFF(HOUR,created_date,approval_time)),'N/A') as avg_time FROM orders where status='Approved' and lead_type='Incoming' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
                                        $total_time=0;
                                        $count_in=db_num_array($in_query);
                                         if($count_in){
                                        while($in_data=db_fetch_array($in_query))
                                        {
                                              
                                           
                                            $time_diff+=diffhours($in_data['created_date'],$in_data['approval_time']);  
                                            if($in_data['avg_time']<200 && $in_data['avg_time']>0)
                                            {
                                                $total_time+=$in_data['avg_time'];
                                            }
                                            
                                        }
                                        $incoming=$total_time/$count_in;
                                        }
                                        //echo $incoming;die;
                                        if($lc && !$bd && !$incoming)
                                        {
                                            $avg=$lc;
                                        }
                                        else if(!$lc && $bd && !$incoming)
                                        {
                                            $avg=$bd;
                                        }
                                        else if(!$lc && !$bd && $incoming)
                                        {
                                            $avg=$incoming;
                                        }
                                        else if($lc && $bd && !$incoming)
                                        {
                                            $avg=($lc+$bd)/2;
                                        }
                                        else if($lc && !$bd && $incoming)
                                        {
                                            $avg=($lc+$incoming)/2;
                                        }
                                        else if(!$lc && $bd && $incoming)
                                        {
                                            $avg=($bd+$incoming)/2;
                                        }
                                        else
                                        {
                                        $avg=($lc+$bd+$incoming)/3;
                                        }
                                       
                                        $lc=number_format($lc,0);
                                        $bd=number_format($bd,0);
                                        $incoming=number_format($incoming,0);
                                        $avg=number_format($avg,0);
                                        ?>
										 <tr>
                                         <td class="text-center">1</td>
                                         <td class="text-center">LC</td>
                                         <td class="text-center"><?=$lc?></td>
                                        </tr>
                                        <tr>
                                         <td class="text-center">2</td>
                                         <td class="text-center">BD</td>
                                         <td class="text-center"><?=$bd?></td>
                                        </tr>
                                        <tr>
                                         <td class="text-center">3</td>
                                         <td class="text-center">Incoming</td>
                                         <td class="text-center"><?=$incoming?></td>
                                        </tr>
                                        <tr>
                                         <th></th>
                                         <th class="text-center">Average</th>
                                         <th class="text-center"><?=$avg?></th>
                                        </tr>
											
									
											</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">DR Action Timespan - VAR</h4>
								
                                <h6 class="card-subtitle">Showing data from <?=date('d-m-Y',strtotime($date_from))?> to <?=date('d-m-Y',strtotime($date_to))?></h6>
								 <div class="table-responsive">
                                    <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">S.No.</th>
                                                <th width="50%" class="text-center">VAR Name</th>
                                                <th class="text-center">LC in Hrs</th>
                                                <th class="text-center">BD in Hrs</th>
                                                <th class="text-center">Incoming in Hrs</th>
                                                <th class="text-center">Average DAT in Hrs</th>
                                        
                                                 
                                            </tr>
                                        </thead>
                                         
                                        <tbody>	
                                        <?php 
                                        $i=1;
                                        $sql=db_query("select id,name from partners where status='Active'");
                                        while($row=db_fetch_array($sql))
                                        {
                                            $lc_query=db_query("select id,created_date,orders.approval_time,convert_date FROM orders where status='Approved' and lead_type='LC' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' and team_id='".$row['id']."' group by id");
                                            $total_time=0;
                                            $count_lc=db_num_array($lc_query);
                                            if($count_lc){
                                            while($lc_data=db_fetch_array($lc_query))
                                            {
                                                if($lc_data['convert_date'])
                                                {
                                                    $time_diff=diffhours($lc_data['convert_date'],$lc_data['approval_time']);
                                                }
                                                else
                                                {
                                                    $time_diff+=diffhours($lc_data['created_date'],$lc_data['approval_time']);  
                                                }
                                                if($time_diff<200)
                                                {
                                                    $total_time+=$time_diff;
                                                }
                                            }
                                            $lc=$total_time/$count_lc;
                                            }
    
                                            $bd_query=db_query("select id,created_date,orders.approval_time,convert_date FROM orders where status='Approved' and lead_type='BD' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."'  and team_id='".$row['id']."' group by id");
                                            $total_time=0;
                                            $count_bd=db_num_array($bd_query);
                                            if($count_bd){
                                            while($bd_data=db_fetch_array($bd_query))
                                            {
                                                
                                                if($bd_data['convert_date'])
                                                {
                                                    $time_diff=diffhours($bd_data['convert_date'],$bd_data['approval_time']);
                                                }
                                                else
                                                {
                                                    $time_diff+=diffhours($bd_data['created_date'],$bd_data['approval_time']);  
                                                }
                                                if($time_diff<200)
                                                {
                                                    $total_time+=$time_diff;
                                                }
                                            }
                                            $bd=$total_time/$count_bd;
                                            }
                                           
                                            $in_query=db_query("select id,created_date,orders.approval_time,convert_date,IFNULL(AVG(TIMESTAMPDIFF(HOUR,created_date,approval_time)),'N/A') as avg_time FROM orders where status='Approved' and lead_type='Incoming'  and team_id='".$row['id']."' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
                                            $total_time=0;
                                            $count_in=db_num_array($in_query);
                                             if($count_in){
                                            while($in_data=db_fetch_array($in_query))
                                            {
                                                  
                                               
                                                $time_diff+=diffhours($in_data['created_date'],$in_data['approval_time']);  
                                                if($in_data['avg_time']<200 && $in_data['avg_time']>0)
                                                {
                                                    $total_time+=$in_data['avg_time'];
                                                }
                                                
                                            }
                                            $incoming=$total_time/$count_in;
                                        }
                                            //echo $incoming;die;
                                            if($lc && !$bd && !$incoming)
                                            {
                                                $avg=$lc;
                                            }
                                            else if(!$lc && $bd && !$incoming)
                                            {
                                                $avg=$bd;
                                            }
                                            else if(!$lc && !$bd && $incoming)
                                            {
                                                $avg=$incoming;
                                            }
                                            else if($lc && $bd && !$incoming)
                                            {
                                                $avg=($lc+$bd)/2;
                                            }
                                            else if($lc && !$bd && $incoming)
                                            {
                                                $avg=($lc+$incoming)/2;
                                            }
                                            else if(!$lc && $bd && $incoming)
                                            {
                                                $avg=($bd+$incoming)/2;
                                            }
                                            else
                                            {
                                            $avg=($lc+$bd+$incoming)/3;
                                            }
                                           
                                            $lc=number_format($lc,0);
                                            $bd=number_format($bd,0);
                                            $incoming=number_format($incoming,0);
                                            $avg=number_format($avg,0);
                                        ?>
										 <tr>
                                         <td class="text-center"><?=$i?></td>
                                         <td class="text-center"><?=$row['name']?></td>
                                         <td class="text-center"><?=$lc?></td>
                                         <td class="text-center"><?=$bd?></td>
                                         <td class="text-center"><?=$incoming?></td>
                                         <td class="text-center"><?=$avg?></td>

                                        </tr>
                                        
                                    <?php
                                    $lc=0;
                                    $bd=0;
                                    $incoming=0;
                                    $avg=0;
                                    $i++; } ?> 
									
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
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
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
        ],
        lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
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
                    autoclose: !0

                });

            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            });

function clear_search()
{
window.location='dr_action_admin.php';
}
    </script>