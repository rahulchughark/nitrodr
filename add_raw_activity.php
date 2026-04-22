<?php include("includes/include.php"); ?>

<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">

      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity for <?= getSingleresult("select company_name from raw_leads where id=" . $_POST['pid']) ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>

    </div>
    <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
    $row_data = db_fetch_array($query);

    $association_query = db_query("select * from activity_log left join raw_leads r on activity_log.pid=r.id where r.product_type_id in (1,2) and activity_log.pid=" . $_POST['pid'] . " and activity_log.call_subject='Fresh Call'");
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
                if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'REVIEWER') {

                  $call_query = db_query("select * from call_subject where 1 order by subject");
                  while ($call_subject = db_fetch_array($call_query)) {  ?>

                    <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                    <?php }
                } else {

                  if ($row_data['role'] == 'TC' || $_SESSION['user_type'] == 'INTERN' || ($_SESSION['user_type'] == 'CLR' &&$_SESSION['role'] == 'ISS')) {
                    if (mysqli_num_rows($association_query) > 0) {
                      $call_query = db_query("select * from call_subject where subject not like '%visit%' and subject!='Fresh Call' order by subject");
                    } else {
                      $call_query = db_query("select * from call_subject where subject not like '%visit%' order by subject");
                    }
                    while ($call_subject = db_fetch_array($call_query)) {  ?>
                      <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                    <?php }
                  } else {
                    if (mysqli_num_rows($association_query) > 0) {

                      $call_query = db_query("select * from call_subject where 1 and subject!='Fresh Call' order by subject");
                    } else {

                      $call_query = db_query("select * from call_subject where 1 order by subject");
                    }
                    while ($call_subject = db_fetch_array($call_query)) {  ?>

                      <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                <?php }
                  }
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
          <button type="submit" class="btn btn-primary" name="save">Save </button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

        </div>
      </form>
    </div>


  </div>

</div>