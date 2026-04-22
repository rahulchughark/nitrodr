<?php include("includes/include.php");



?>
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Add Activity for <?= getSingleresult("select company_name from orders where id=" . $_POST['pid']) ?></h4>
    </div>
    <div class="modal-body">
      <form action="#" method="post" class="form p-t-20">
        <div class="form-group">
          <label for="exampleInputuname">Call Subject</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-layers-alt"></i></div>
            <select required name="call_subject" class="form-control">
              <option value="">---Select---</option>
              <?php
              if ($_SESSION['role'] == 'TC') {
                $call_query = db_query("select * from call_subject where subject not like '%visit%'");
                while ($call_subject = db_fetch_array($call_query)) {  ?>
                  <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                <?php }
              } else {
                $call_query = db_query("select * from call_subject where 1");
                while ($call_subject = db_fetch_array($call_query)) {  ?>

                  <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
              <?php }
              } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputuname">Visit/Profiling Remarks</label>
          <div class="input-group">
            <div class="input-group-addon"><i class="ti-layers-alt"></i></div>
            <textarea required value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" id="exampleInputuname" placeholder=""></textarea>
          </div>
        </div>


        <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

        <div class="modal-footer">
          <input type="submit" name="save" value="Save" class="btn btn-success waves-effect waves-light m-r-10" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>
      </form>
    </div>


  </div>

</div>