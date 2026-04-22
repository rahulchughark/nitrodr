<?php include("includes/include.php"); 
include_once('helpers/DataController.php');
$dataObj = new DataController;

if ($_REQUEST['edit_id']) {
    $sql = db_query("select * FROM learning_zone_attachment where status=1 and id=".$_REQUEST['edit_id']." ORDER BY id desc");
    $row = db_fetch_array($sql);
    @extract($row);
} 
if($partner_access != '' && $partner_access != null){
  $partners = explode(",",$partner_access);
}else{
  $partners[] = '';
}
if($users_access != '' && $users_access != null){
  $userss = explode(",",$users_access);
}else{
  $userss[] = '';
}
// print_r($partners);die;
?>


<div class="modal-dialog modal-dialog-centered modal-lg">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Admin Controls</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>

    </div>
    <div class="modal-body">
      <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
            <input type="hidden" name="eid" class="form-control" value="<?= $_REQUEST['edit_id'] ?>">

        <?php
          if ($_SESSION['sales_manager'] != 1) {
              $res = db_query("select * from partners where status='Active'");
          } else {
              $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
          }
        ?>
        <div id="manage_partner_access">
          <h5 class="font-weight-bold mb-3">Manage Partner Access</h5>
          <div class="form-group">
            <label>Partner Name </label>
        <select name="partner[]" id="partner" class="multiselect_partner form-control" required data-live-search="true" multiple>
            <?= $dataObj->getActivePartners($partners); ?>
          
        </select>
          </div>
          <div class="form-group">
            <label>User Type</label>
              <select name="user_type[]" id="user_type" required class="form-control multiselect_user_type" multiple>
                      <option value="USR" <?= (in_array('USR', $userss) ? 'selected' : '') ?>>User</option>
                      <option value="MNGR" <?= (in_array('MNGR', $userss) ? 'selected' : '') ?>>Manager</option>
              </select>
          </div>
          <!-- <div class="form-group">
            <label>User Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter user email">
          </div>
          <div class="form-group">
            <label>Assign Category Access</label>
            <select multiple class="form-control">
              <option>Product Pitch &amp; Marketing Documents</option>
              <option>Commercial Documents</option>
              <option>Technical &amp; Operational Documents</option>
              <option>Training &amp; Support Resources</option>
              <option>Optional / Value Add Resources</option>
              <option>ATL</option>
            </select>
          </div>
        <div> -->
          
        <!-- Modal Footer (initially hidden) -->
        <div id="footer-buttons">
          <div class="modal-footer justify-content-center border-0 pb-0">
              <input type="submit" name="edit_data" value="Save" class="btn btn-primary" />
          </div>
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

<script>

  $(document).ready(function() {
      $('.multiselect_partner').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'Select Partner',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeFilterClearBtn:true
    });
      $('.multiselect_user_type').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'Select User Type',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeFilterClearBtn:true
    });
  });
</script>