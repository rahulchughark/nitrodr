<?php include("includes/include.php");
 
$users=db_query("select callers.id as caller_id,callers.name from callers join users on callers.user_id=users.id where users.user_type='CLR'");
 
?>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Changing ownership for <b><?=getSingleresult("select company_name from orders where id=".$_POST['id'])?></b></h5>
      </div>
      <div class="modal-body">
        <form action="#" method="post" class="form p-t-20">
                                    <div class="form-group">
                                        <label for="exampleInputuname">Select User</label>
                                        <div class="input-group">
                                            <select name="new_user" class="form-control">
                                            <option value=''>---Select---</option>
                                            <?php while($users_data=db_fetch_array($users))
                                            {?>
                                            <option value="<?=$users_data['caller_id']?>"><?=$users_data['name']?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
          <input type="hidden" name="id" value="<?=$_POST['id']?>" />                           
                                    
        <div class="modal-footer">
	  <input type="submit" name="save_new_user" value="Save" class="btn btn-success waves-effect waves-light m-r-10" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		
      </div>  </form>                           
      </div>
     
	   
    </div>
 
  </div>