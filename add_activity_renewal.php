<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);
//print_r($_POST['pid']);
//print_r("select company_name from orders where id=" . $_POST['pid']);
?>
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity for <?= getSingleresult("select company_name from orders where id=" . $_POST['pid']) ?> </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
    $row_data = db_fetch_array($query);

 
    ?>
    <div class="modal-body">
      <form action="#" method="post" class="form p-t-20">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Call Subject</label>

              <select required name="call_subject" class="form-control">
                <option value="">---Select---</option>
                <?php
                if ($row_data['role'] == 'TC') {
                  
                    $call_query = db_query("select * from call_subject where subject not like '%visit%' and subject not like '%association%' order by subject");
                  
                  while ($call_subject = db_fetch_array($call_query)) {  ?>
                    <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                  <?php }
                } else {
                 
                    $call_query = db_query("select * from call_subject where 1 and subject not like '%association%' order by subject");
                

                  while ($call_subject = db_fetch_array($call_query)) {  ?>

                    <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                <?php }
                } ?>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Visit/Profiling Remarks</label>

              <textarea required value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" id="exampleInputuname" placeholder=""></textarea>
            </div>
          </div>
        </div>

        <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

        <div class="modal-footer">
          <input type="submit" name="save" value="Save" class="btn btn-primary" />
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

        </div>
      </form>
    </div>


  </div>

</div>