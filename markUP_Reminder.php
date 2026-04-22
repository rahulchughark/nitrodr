<?php include("includes/include.php"); ?>


<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Follow-up Reminder
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="#" method="post" class="form p-t-20">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <input class="form-control" name="company_name" value="<?= $_POST['company_name'] ?>" />
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Follow-up Type</label>
                            <input class="form-control" name="follow_type" value="<?= $_POST['follow_type'] ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Follow-up Date</label>
                            <input type="date" class="form-control" id="datepicker-close-date" name="follow_up_date" value="<?= $_POST['follow_up_date'] ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input">Follow-up Time</label>
                            <input class="timepicker form-control" name="follow_up_time" value="<?= $_POST['follow_up_time'] ?>" />
                        </div>
                    </div>

                </div>

                <input type="hidden" name="lead_id" value="<?= $_POST['id'] ?>" />

                <div class="modal-footer">
                    <button type="button" name="mark_complete" onclick="markComplete(<?= $_POST['id'] ?>,'<?= $_POST['company_name'] ?>','<?= $_POST['follow_up_date']?>','<?= $_POST['follow_up_time']?>')" class="btn btn-primary" style="display: left;">Mark as Complete</button>

                    <button type="button" name="reschedule_reminder" onclick="reschedule(<?= $_POST['id'] ?>,'<?= $_POST['company_name'] ?>','<?= $_POST['follow_up_date']?>','<?= $_POST['follow_up_time']?>','<?= $_POST['follow_type'] ?>','<?= $_POST['remind_before']?>')" class="btn btn-primary" style="display: left;">Re-Schedule</button>

                    <button type="button" class="btn btn-light" data-dismiss="modal">Skip</button>

                </div>
            </form>
        </div>


    </div>
</div>

<script>
    function markComplete(a, company_name,follow_up_date,follow_up_time) {
        $.ajax({
            type: 'POST',
            url: 'add_activity_followUp.php',
            data: {
                pid: a,
                company_name: company_name,
                follow_up_date:follow_up_date,
                follow_up_time:follow_up_time,

            },
            success: function(response) {
                $("#mark_complete").html();
                $("#mark_complete").html(response);
                $('#mark_complete').modal('show');
            }
        });
    }

    function reschedule(a, company_name,follow_up_date,follow_up_time,follow_type,remind_before) {
        $.ajax({
            type: 'POST',
            url: 'reschedule_reminder.php',
            data: {
                pid: a,
                company_name: company_name,
                follow_up_date:follow_up_date,
                follow_up_time:follow_up_time,
                follow_type:follow_type,
                remind_before:remind_before,
                comments:'<?= $_POST['comments']?>'

            },
            success: function(response) {
                $("#mark_complete").html();
                $("#mark_complete").html(response);
                $('#mark_complete').modal('show');
            }
        });
    }
</script>