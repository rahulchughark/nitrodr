<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);

?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity Call for <?= $_POST['company_name'] ?>
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
                                <option value="Follow-up Call" selected>Follow-up Call</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="example-text-input">Visit/Profiling Remarks</label>
                            <textarea required value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" placeholder=""></textarea>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />
                <input type="hidden" name="follow_up_date" value="<?= $_POST['follow_up_date'] ?>" />
                <input type="hidden" name="follow_up_time" value="<?= $_POST['follow_up_time'] ?>" />

                <div class="modal-footer">
                    <input type="submit" name="save_activity_markup" value="Save" class="btn btn-primary" />
                    <!-- <button type="submit" class="btn btn-primary" name="save">Save </button> -->
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                </div>
            </form>
        </div>


    </div>
</div>