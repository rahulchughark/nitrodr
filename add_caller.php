<?php include("includes/include.php"); ?>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Activity for <?=getSingleresult("select company_name from orders where id=".$_POST['pid'])?></h4>
      </div>
      <div class="modal-body">
        <form action="#" method="post" class="form p-t-20">
                                    <div class="form-group">
                                        <label for="exampleInputuname">Comment</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="ti-layers-alt"></i></div>
                                            <textarea required  value="<?=$goal_data['daily']?>" name="remarks" class="form-control" id="exampleInputuname" placeholder=""></textarea>
                                        </div>
                                    </div>
                                    
          <input type="hidden" name="pid" value="<?=$_POST['pid']?>" />                           
                                    
        <div class="modal-footer">
	  <input type="submit" name="save" value="Save" class="btn btn-success waves-effect waves-light m-r-10" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		
      </div>  </form>                           
      </div>
     
	   
    </div>
 
  </div>