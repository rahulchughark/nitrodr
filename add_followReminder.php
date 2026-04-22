<?php include("includes/include.php"); ?>


<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Create Follow-up
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="#" method="post" class="form p-t-20">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Follow-up Type</label>
                            <select required name="follow_type" class="form-control">
                                <option value="">---Select---</option>
                                <option value="Follow-up call" <?php ('Follow-up call'==$_POST['follow_type'])?'selected':'' ?>>Follow-up call (TC)</option>
                                <option value="Follow-up visit"<?php ($_POST['follow_type']=='Follow-up visit')?'selected':'' ?>>Follow-up visit (BO & Sales Executive)</option>
                                <option value="Reminder to escalate"<?php ($_POST['follow_type']=='Reminder to escalate')?'selected':'' ?>>Reminder to escalate (All)</option>
                                <option value="Tech support"<?php ($_POST['follow_type']=='Tech support')?'selected':'' ?>>Tech support (All)</option>
                                <option value="Send Quote"<?php ($_POST['follow_type']=='Send Quote')?'selected':'' ?>>Send Quote (TC,BO, Sales Executive)</option>
                                <option value="Send Email"<?php ($_POST['follow_type']=='Send Email')?'selected':'' ?>>Send Email (All)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Follow-up Date</label>
                            <input type="date" class="form-control" id="datepicker-close-date" name="follow_up_date"  value="<?= $_POST['follow_up_date']?>"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Follow-up Time</label>
                            <input class="timepicker form-control" name="follow_up_time" value="<?= $_POST['follow_up_time']?>"/>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Comments</label>
                            <textarea name="comments" class="form-control" placeholder=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-4"></div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Remind me Before</label>
                            <select required name="reminder" class="form-control">
                                <option value="">---Select---</option>
                                <option value="10" <?php ($_POST['remind_before']==10)?'selected':'' ?>>10 mins</option>
                                <option value="15"<?php ($_POST['remind_before']==15)?'selected':'' ?>>15 mins</option>
                                <option value="30"<?php ($_POST['remind_before']==30)?'selected':'' ?>>30 mins</option>

                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="lead_id" value="<?= $_POST['id'] ?>" />

                <div class="modal-footer">
                    <input type="submit" name="schedule_reminder" value="Schedule" class="btn btn-primary" />
                    <!-- <button type="submit" class="btn btn-primary" name="save">Save </button> -->
                    <button type="button" class="btn btn-light" data-dismiss="modal">Skip</button>

                </div>
            </form>
        </div>


    </div>
</div>

<script>
    $(function() {
        $('#datepicker-close-date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d',
            autoclose: !0

        });

    });
    
    $(document).ready(function() {
        $('.timepicker').timepicker({
            timeFormat: 'HH:mm',
            // year, month, day and seconds are not important
            minTime: new Date(0, 0, 0, 8, 0, 0),
            maxTime: new Date(0, 0, 0, 15, 0, 0),
            // time entries start being generated at 6AM but the plugin 
            // shows only those within the [minTime, maxTime] interval
            startHour: 6,
            // the value of the first item in the dropdown, when the input
            // field is empty. This overrides the startHour and startMinute 
            // options
            startTime: new Date(0, 0, 0, 8, 20, 0),
            // items in the dropdown are separated by at interval minutes
            interval: 1
        });
    });
</script>