<?php include("includes/include.php"); 

if ($_REQUEST['edit_id']) {
    $sql = db_query("select * FROM learning_zone where status=1 and type='Doc' and id=".$_REQUEST['edit_id']." ORDER BY id desc");
    $row = db_fetch_array($sql);
    @extract($row);
} 
?>

<div class="modal-dialog modal-dialog-centered">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title ">Edit Product Document</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body">
      <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
        <div class="row">

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Title<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" value="<?= $title?>" required>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">About Tutorial<span class="text-danger">*</span></label>
              <textarea class="form-control" name="desc" required><?= $description?></textarea>
            </div>
          </div>

        </div>

        <div class="modal-footer justify-content-center border-0 pb-0">
          <input type="hidden" name="eid" value=<?= $_REQUEST['edit_id'] ?> />
          <input type="submit" name="update_data" value="Update" class="btn btn-primary" />
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