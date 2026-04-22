<?php include("includes/include.php"); ?>
<style>

.dataTables_wrapper {
  max-width: 100%;
}
</style>
<div class="modal-dialog  modal-xl modal-dialog-centered">
<?php 
// print_r($_POST);die;
$urlCond = '';
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.expected_close_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.expected_close_date)>='".$requestData['d_from']."' and DATE(o.expected_close_date)<='".$requestData['d_to']."'";	
		}
	}elseif($requestData['d_type']== 'actioned_date'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.approval_time)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.approval_time)>='".$requestData['d_from']."' and DATE(o.approval_time)<='".$requestData['d_to']."'";	
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
		}
	}
    $urlCond.="&dtype=".$requestData['d_type']."&d_from=".$requestData['d_from']."&d_to=".$requestData['d_to'];
}
$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and o.tag in ('".implode("','",$tagFtr)."')";
    foreach ($tagFtr as $pr) {
		$urlCond.= "&tag[]=$pr";
	}
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
    foreach ($statusFtr as $pr) {
		$urlCond.= "&status[]=$pr";
	}
}
$partnerFtr =  json_decode($_REQUEST['partner']);
$usersFtr =  json_decode($_REQUEST['users']);
if($partnerFtr != '' && !$usersFtr)
{
	$teamUsers = db_query("SELECT id from users where team_id in (".implode(',',$partnerFtr).")");
	$usrrArr = [];
	while ($usr = db_fetch_array($teamUsers)){
		$usrrArr[] = $usr['id'];
        $urlCond.= "&users[]=".$usr['id'];
	}
	$dat.=" AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.allign_to IN (" . implode(",", $usrrArr) . ")))";
}
if($usersFtr != '')
{
	$dat.=" AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
    foreach ($usersFtr as $pr) {
		$urlCond.= "&users[]=$pr";
	}
}
$school_boardFtr = json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
    foreach ($school_boardFtr as $pr) {
		$urlCond.= "&school_board[]=$pr";
	}
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and o.state in (".implode(",",$statesFtr).")";
    foreach ($statesFtr as $pr) {
		$urlCond.= "&state[]=$pr";
	}
}
$sourceFtr =  json_decode($_REQUEST['source']);
if($sourceFtr != '')
{
	$dat.= " and o.source in ('".implode("','",$sourceFtr)."')";
    foreach ($sourceFtr as $pr) {
		$urlCond.= "&source[]=$pr";
	}
}

    if($_POST['type'] == 'substage'){
        $sql=db_query("SELECT s.name FROM sub_stage as s where s.stage_name='Demo'");
    }else{
        $sql=db_query("SELECT s.stage_name as name FROM stages as s");
    }

?>
    <!-- Modal content-->
    <form id="myform" class="w-100">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Stage Order Report</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
            <div class="modal-body">
            <table id="leadss" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" data-toggle="table" data-height="wfheight" data-mobile-responsive="true">
            <thead>
              <tr>
             <th>S.No.</th>
             <th>Stage Name</th>
             <th>Lead</th>
             <th>Opportunity</th>            
             </tr>
                                </thead>

                                <tbody>
             <?php $i=1; while($row=db_fetch_array($sql))
             {  ?>
            <tr>
            <td><?=$i?></td>
            <td><?=$row['name']?></td>
            <?php if($_POST['type'] == 'substage'){ ?>
                <td><a href="search_orders.php?stage[]=Demo&sub_stage[]=<?=$row['name']?>&lead_status[]=<?= $_POST['lead_status'] ?><?= $urlCond ?>" target="_blank"><?= getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.is_opportunity=0 and o.add_comm='".$row['name']."' and o.lead_status='".$_POST['lead_status']."' $dat") ?></a></td>
                <td><a href="manage_opportunity.php?stage[]=Demo&sub_stage[]=<?=$row['name']?>&lead_status[]=<?= $_POST['lead_status'] ?><?= $urlCond ?>" target="_blank"><?= getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.is_opportunity=1 and o.add_comm='".$row['name']."' and o.lead_status='".$_POST['lead_status']."' $dat") ?></a></td>
            <?php } else {?>
                <td><a href="search_orders.php?stage[]=<?=$row['name']?>&lead_status[]=<?= $_POST['lead_status'] ?><?= $urlCond ?>" target="_blank"><?= getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.is_opportunity=0 and o.stage='".$row['name']."' and o.lead_status='".$_POST['lead_status']."' $dat") ?></a></td>
                <td><a href="manage_opportunity.php?stage[]=<?=$row['name']?>&lead_status[]=<?= $_POST['lead_status'] ?><?= $urlCond ?>" target="_blank"><?= getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.is_opportunity=1 and o.stage='".$row['name']."' and o.lead_status='".$_POST['lead_status']."' $dat") ?></a></td>
            <?php } ?>
             
              </tr>
                                    <?php $i++; } ?>
                                </tbody>
                            </table>
             </div>
                                    
                                    
                             
                                    
        <div class="modal-footer">

        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
		
      </div>  </form>                           
      </div>
     
	   
    </div>
 
  </div>
  <script>
  $(document).ready(function() {
      //     $('#myTable').DataTable();
        $(document).ready(function() {
            var dataTable = $('#leadss').DataTable( {
                "stateSave": true,
		dom: 'Bfrtip',
    language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
             
                error: function(){  // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display","none");
 
                },
            //"order": [[ 5, "desc" ]],
            columnDefs: [
               { orderable: false, targets: 0 }
            ],

           
        } );
            // Order by the grouping
            $('#leadss tbody').on('click', 'tr.group', function() {
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
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
  </script>