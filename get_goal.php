<?php include("includes/include.php");
$_POST['pid'] = intval($_POST['pid']);
$goal=db_query("select * from lead_goals where partner_id=".$_POST['pid']);
$goal_data=db_fetch_array($goal);

?>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <?php /*?>  <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Goals for <?=getSingleresult("select name from partners where id=".$_POST['pid'])?></h4><?php */?>
        
         <h4 class="modal-title ">Goals for <?=getSingleresult("select name from partners where id=".$_POST['pid'])?></h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <form action="#" method="post" class="form p-t-20">
                                    <div class="form-group">
                                        <label for="exampleInputuname">Daily Goal</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="ti-flag"></i></div>
                                            <input required type="text" value="<?=$goal_data['daily']?>" name="daily" class="form-control" id="exampleInputuname" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Weekly Goal</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="ti-flag"></i></div>
                                            <input required type="text" value="<?=$goal_data['weekly']?>"  name="weekly" class="form-control" id="exampleInputEmail1" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd1">Monthly Goal</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="ti-flag"></i></div>
                                            <input required type="text"  value="<?=$goal_data['monthly']?>" name="monthly" class="form-control" id="pwd1" placeholder="">
                                        </div>
                                    </div>
          <input type="hidden" name="pid" value="<?=$_POST['pid']?>" />                           
                                    
        <div class="modal-footer">
	  <?php /*?><input type="submit" name="save" value="Save" class="btn btn-success waves-effect waves-light m-r-10" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button><?php */?>
        
          <input type="submit" name="save" value="Save" class="btn btn-primary waves-effect waves-light m-r-10" />
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        
		
      </div>  </form>                           
      </div>
     
	   
    </div>
 
  </div>