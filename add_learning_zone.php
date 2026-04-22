<?php include("includes/include.php"); ?>

<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title ">Add Learning Zone </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
      <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
        <div class="row">

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Title<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required onkeyup="validateInputs()">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">About Tutorial<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="desc" id="desc" required onkeyup="validateInputs()">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Upload Video<span class="text-danger">*</span></label>
              <input required type="file" name="attachment" class="form-control" id="exampleInputuname" placeholder="" aria-invalid="false" accept="video/*">
            </div>
          </div>

        </div>

        <div class="text-center mt-2">
          <input type="submit" name="save_data" value="Save" id="submitBtn" disabled class="btn btn-primary" />
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

    function validateInputs() {
                const title = document.getElementById('title');
                const desc = document.getElementById('desc');
                const submitBtn = document.getElementById('submitBtn');
                // alert(desc.value)
                if (title.value.trim() !== '' && desc.value.trim() !== '') {
                  submitBtn.disabled = false;
                } else {
                  submitBtn.disabled = true;
                }
            }
</script>