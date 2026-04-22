<?php include("includes/include.php"); ?>

<div class="modal-dialog modal-dialog-centered">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title ">Add Learning Center </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
      <form action="#" method="post" class="form p-t-20">
        <div class="row">

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Module<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="l_module" required>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">URL<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="l_url" required>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Status</label>
              <select name="status" class="form-control">
                <option value="1">Admin</option>
                <option value="2">Partner</option>
              </select>
            </div>
          </div>

        </div>

        <div class="mt-3 text-center">
          <input type="submit" name="save_activity" value="Save" class="btn btn-primary" />
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>


    </div>

</div>
<script>
    var regex = new RegExp("(.*?)\.(csv)$");

    function triggerValidation(el) {
        if (!(regex.test(el.value.toLowerCase()))) {
            el.value = '';
            alert('Please select correct file format');
        }
    }
</script>