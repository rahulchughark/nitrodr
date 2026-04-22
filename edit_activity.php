<?php include("includes/include.php"); ?>


<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<div class="modal-dialog modal-dialog-centered">
  <?php
  $_POST['id'] = intval($_POST['id']);
$userId = $_SESSION['user_id'];
$condition = ($_SESSION['user_type'] == 'ADMIN') ? "" : " AND alr.user_id = $userId";

$sql = db_query("SELECT al.*, alr.reminder,alr.reminder_date,alr.reminder_time 
                 FROM activity_log AS al 
                 LEFT JOIN activity_log_reminder alr ON alr.activity_log_id = al.id  
                 WHERE al.id = " . intval($_POST['id']) . $condition);

  // $sql = db_query("select al.* from activity_log as al 
  //                 LEFT JOIN activity_log_reminder alr ON alr.activity_log_id = al.id  
  //                 where al.id=" . $_POST['id']." and alr.user_id =".$_SESSION['user_id']);


  $row = db_fetch_array($sql);

  // echo "<pre>";
  // print_r($row);
  // exit;
  ?>
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Edit Activity Call for <?= $_POST['company_name'] ?>
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
              <label for="example-text-input">Call Subject</label>

              <select required name="call_subject" class="form-control">
                <option value="">---Select---</option>
                <?php $call_query = db_query("select * from call_subject where 1");
                while ($call_subject = db_fetch_array($call_query)) { ?>
                  <option <?= (($row['call_subject'] == $call_subject['subject']) ? 'selected' : '') ?> value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                <?php  } ?>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Visit/Profiling Remarks</label>

              <textarea required value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" id="exampleInputuname" placeholder=""><?= $row['description'] ?></textarea>
            </div>
          </div>


          <!-- Reminder Toggle -->
          <div class="col-md-12">
            <div class="form-group">
              <label>Reminder</label>
              <label class="switch">
                <input type="checkbox" name="reminder" value="1"
                <?= !empty($row['reminder']) ? 'checked' : '' ?>
                id="reminderToggle"
                onchange="toggleReminderFields()">
                <span class="slider round"></span>
              </label>
            </div>
          </div>

          <!-- Date + Time Fields -->
          <!-- <div class="row" id="reminderFields" style="display: none;"> -->
            <div class="row" id="reminderFields" style="display: <?= !empty($row['reminder']) ? '' : 'none' ?>;">


            <div class="col-md-6">
              <div class="form-group">
                <label for="reminder_date">Date <span class="text-danger">*</span></label>
                <input type="date" name="reminder_date" value="<?= $row['reminder_date'] ?>" id="reminder_date" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="reminder_time">Time <span class="text-danger">*</span></label>
                <input type="time" name="reminder_time" value="<?= $row['reminder_time'] ?>" id="reminder_time" class="form-control">
              </div>
            </div>
            
          </div>

          <?php $status = db_query("select status from orders where id='" . $row['pid'] . "'");
        $status_arr = db_fetch_array($status);

        $plan_of_action = getSingleresult("select action_plan from activity_log where id='" . $_POST['id'] . "' order by id desc limit 1");
        //print_r($plan_of_action);

        if ($status_arr['status'] == 'Approved') { ?>
          <!-- <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Plan of Action</label>
              <select name="action_plan" class="form-control">
                <option value="">Select Plan of Action</option>
                <option value="Drop" <?= $plan_of_action == 'Drop' ? 'selected' : '' ?>>Drop</option>
                <option value="Need More Validation" <?= $plan_of_action == 'Need More Validation' ? 'selected' : '' ?>>Need More Validation</option>
                <option value="Turns Negative" <?= $plan_of_action == 'Turns Negative' ? 'selected' : '' ?>>Turns Negative</option>
              </select>
            </div>
          </div> -->
        <?php } ?>
          <input type="hidden" name="pid" value="<?= $_POST['id'] ?>" />

          <div class="mt-2 text-center w-100">
            <input type="submit" name="activity_edit" value="Save" class="btn btn-primary" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

          </div>
      </form>
    </div>


  </div>


  <script>
  function toggleReminderFields() {
    var checkbox = document.getElementById('reminderToggle');
    var dateField = document.getElementById('visit_date');
    var timeField = document.getElementById('visit_time');
    var container = document.getElementById('reminderFields');

    if (checkbox.checked) {
      container.style.display = 'flex';
      dateField.setAttribute('required', 'required');
      timeField.setAttribute('required', 'required');
    } else {
      container.style.display = 'none';
      dateField.removeAttribute('required');
      timeField.removeAttribute('required');
    }
  }
</script>