<?php include("includes/include.php");

$_POST['team_id'] = intval($_POST['team_id']);
$_POST['id'] = intval($_POST['id']);

if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS'  || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'OPERATIONS EXECUTIVE' || $_SESSION['user_type'] == 'RM' || $_SESSION['user_type'] == 'EM') {
  $team_id = getSingleresult("select team_id from orders where id=" . $_POST['id']);
  $users = db_query("select id,name from users where team_id = " . $team_id);
  
} else {
  $users = db_query("select id,name from users where team_id=" . $_POST['team_id']);
}

?>
<div class="modal-dialog modal-dialog-centered">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Changing ownership for <b><?= getSingleresult("select school_name from orders where id=" . $_POST['id']) ?>
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <form action="#" method="post" class="form p-t-20">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Select User</label>

              <select name="new_user" class="form-control">
                <option value='' disabled>---Select---</option>
                <?php while ($users_data = db_fetch_array($users)) { ?>
                  <option value="<?= $users_data['id'] ?>"><?= $users_data['name'] ?></option>
                <?php } ?>
              </select>

            </div>
          </div>
        </div>
   
        <input type="hidden" name="id" value="<?=$_POST['id']?>" />    

    <div class="mt-3 text-center">
      <button type="submit" class="btn btn-primary" name="save_new_user">Save</button>
      <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
    </div>

    </form>
  </div>


</div>

</div>