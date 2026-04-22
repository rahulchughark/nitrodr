<?php include("includes/include.php"); 
$goal=db_query("select * from activity_log where pid='".$_POST['pid']."' and activity_type='".$_POST['type']."'");
 $count=mysqli_num_rows($goal);
?>

<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activities for <?php if($_POST['type']!='Upgrade'){ echo getSingleresult("select company_name from orders where id=".$_POST['pid']); } else {  echo getSingleresult("select eu_name from upgrade_leads where id=".$_POST['pid']); }
			?></h4>
      </div>
      <div class="modal-body">
        <form action="#" method="post" class="form p-t-20">
	 
		<?php $i=1; if($count){ while($data=db_fetch_array($goal)) { ?>
                                    <div class="form-group">
                                        <label for="exampleInputuname">Visit/Profiling Remarks <?=$i?>  (<?=date('d-m-Y H:i:s',strtotime($data['created_date']))?>)</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="ti-layers-alt"></i></div>
                                            <textarea required   name="remarks" class="form-control" id="exampleInputuname" placeholder=""><?=$data['description']?></textarea>
                                        </div>
                                    </div>
                                    <?php  $i++;
		} } else { echo '<h4 class="title">No Data Found!</h4>';  }?>
		 
          <input type="hidden" name="pid" value="<?=$_POST['pid']?>" />                           
                                    
        <div class="modal-footer">
	     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		
            </div>  
          </form>  


      </div>
     
	   
    </div>
 
  </div>