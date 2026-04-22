<?php include('includes/header.php');
$_GET['f_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_GET['f_date']);
$_GET['t_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_GET['t_date']);


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
                            <li class="breadcrumb-item active">DR Action Report</li>
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
                            <div class="card-body">
                                <h4 class="card-title">DR Action Timespan Report</h4>
								  <div style="float:right;margin-right:20px">
                                  <form method="get" name="search">
                            <div class="row">
                                <div style="float:right;margin:20px">

                                    <input type="text" value="<?php echo @$_GET['f_date'] ?>" class="datepicker" id="f_date" name="f_date" placeholder="Date" />
                                    <input type="text" value="<?php echo @$_GET['t_date'] ?>" class="datepicker" id="t_date" name="t_date" placeholder="Date" />

                                    <input type="submit" value="Search" class="btn btn-primary" />
                                    <input type="button" value="Clear" class="btn btn-danger"  onclick="clear_search()" />

                                </div>
                            </div>
                        </form>

                     </div> 
                                <h6 class="card-subtitle">Showing data from <?=date('d-m-Y',strtotime($date_from))?> to <?=date('d-m-Y',strtotime($date_to))?></h6>
								 <div class="table-responsive m-t-40">
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
                                        $lc_query=db_query("select id,created_date,orders.approval_time,convert_date FROM orders where status='Approved' and lead_type='LC' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' and team_id='".$_SESSION['team_id']."' group by id");
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

                                        $bd_query=db_query("select id,created_date,orders.approval_time,convert_date FROM orders where status='Approved' and lead_type='BD' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."'  and team_id='".$_SESSION['team_id']."' group by id");
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
                                       
                                        $in_query=db_query("select id,created_date,orders.approval_time,convert_date,IFNULL(AVG(TIMESTAMPDIFF(HOUR,created_date,approval_time)),'N/A') as avg_time FROM orders where status='Approved' and lead_type='Incoming'  and team_id='".$_SESSION['team_id']."' and date(created_date) BETWEEN '".$date_from."' and '".$date_to."' group by id");
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
                                         <th class="text-right">Average</th>
                                         <th class="text-center"><?=$avg?></th>
                                        </tr>
											
									
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
window.location='dv_report.php';
}
    </script>