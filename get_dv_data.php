<?php include("includes/include.php");

$goal=db_query("select o.id,o.company_name,o.is_dr,o.created_date,o.created_by,o.lead_type,o.call_type from orders as o left join activity_log on o.id=activity_log.pid where o.created_by='".$_POST['uid']."' and (date(o.created_date)='".$_POST['date']."' or date(activity_log.created_date)='".$_POST['date']."' ) and o.is_dr=1");

// "select o.id,o.company_name,o.is_dr,o.created_date,o.created_by,o.lead_type,o.call_type from orders as o left join activity_log on o.id=activity_log.pid where o.created_by='".$_POST['uid']."' and (date(o.created_date)='".$_POST['date']."' or date(activity_log.created_date)='".$_POST['date']."' ) and o.is_dr=1";
// print_r($goal);

//$goal_data=db_fetch_array($goal);

?>

<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Daily Visit Details- <?=$_POST['date']?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
       
      <div class="modal-body">
				<table style="clear: both" class="table table-bordered table-striped" id="user">
 <tbody>
 <tr>
 <th>S.No.</th>
 <th>Account Name</th>
 <th>Lead Type</th>
 <th>Call Type</th>
 </tr>
 <?php $i=1; while($row=db_fetch_array($goal))
 {  @extract($row);?>
                                        <tr>
                                            <td > <?=$i?></td>
                                            <td ><?=$company_name?></td>
                                            <td><?=$lead_type?></td>
                                            <td><?=getSingleresult("select name from call_type where id=".$call_type)?></td>
                                        </tr>
 <?php $i++; } ?>
                                    </tbody>
                                </table>				
      </div>
     
	   
    </div>
 
  </div>