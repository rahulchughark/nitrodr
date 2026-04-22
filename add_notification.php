<style>
.green-color {
color:green;
}
.red-color {
color:red;
}
</style>
<?php include("includes/include.php");

$_POST['id'] = intval($_POST['id']);

?>
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Change lead type LC for <?= $_POST['company_name'] ?>
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
    $row_data = db_fetch_array($query);

    $lead_data = db_query("select * from orders where id=".$_POST['id']);
    $row = db_fetch_array($lead_data);
    //print_r($row);
    ?>
    <div class="modal-body">
      <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
        <div class="row">
        <div class="col-md-4">
            <div class="form-group">
              <label for="example-text-input">Full Name&nbsp;<?= ($row['eu_name']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times red-color " ></i>' ?></label>
              <input name="full_name" value="<?= $row['eu_name']?>" type="text" readonly class="form-control" >
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="example-text-input">Email&nbsp;<?= ($row['eu_email']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>
              <input name="email" readonly value="<?= $row['eu_email']?>" class="form-control" placeholder="">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="example-text-input">Mobile&nbsp;<?= ($row['eu_mobile']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>
              <input name="mobile" readonly value="<?= $row['eu_mobile']?>" class="form-control" placeholder="">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="example-text-input">Landline Number&nbsp;<?= ($row['eu_landline']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>
              <input name="landline" readonly value="<?= $row['eu_landline']?>" class="form-control" placeholder="">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="example-text-input">No.of licenses&nbsp;<?= ($row['quantity']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>
              <input name="quantity" readonly value="<?= $row['quantity']?>" class="form-control" placeholder="">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="example-text-input">Account Visited&nbsp;<?= ($row['account_visited']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>
              <input name="acc_visited" readonly value="<?= $row['account_visited']?>" class="form-control" placeholder="">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
            
          <label for="example-text-input">Usage Confirmation Received from<span class="text-danger">*</span>&nbsp;<?= ($row['confirmation_from']!='')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>

          <select name="confirmation_received" class="form-control"  placeholder="" required>
          <option value="">---Select---</option>
          <option <?= (($row['confirmation_from'] == 'Graphic Designer') ? 'selected' : '') ?> value="Graphic Designer">Graphic Designer</option>
          <option <?= (($row['confirmation_from'] == 'IT Manager') ? 'selected' : '') ?> value="IT Manager">IT Manager</option>
          <option <?= (($row['confirmation_from'] == 'IT Executive') ? 'selected' : '') ?> value="IT Executive">IT Executive</option>
          <option <?= (($row['confirmation_from'] == 'Reception') ? 'selected' : '') ?> value="Reception">Reception</option>
          <option <?= (($row['confirmation_from'] == 'Employee') ? 'selected' : '') ?> value="Employee">Employee</option>
          <option <?= (($row['confirmation_from'] == 'Customer Reference') ? 'selected' : '') ?> value="Customer Reference">Customer Reference</option>
          <option <?= (($row['confirmation_from'] == 'Other') ? 'selected' : '') ?> value="Other">Other</option>

      </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
          <label for="example-search-input">Role<span class="text-danger">*</span>&nbsp;<?= (($row['eu_role']=='Tech. Buyer' || $row['eu_role']=='Decision Maker')?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>') ?></label>

          <select name="role" class="form-control" placeholder="" aria-invalid="false" required>
              <option value="">---Select---</option>
              <option <?= (($row['eu_role'] == 'Tech. Buyer') ? 'selected' : '') ?> value="Tech. Buyer">Tech. Buyer</option>
              <option <?= (($row['eu_role'] == 'Decision Maker') ? 'selected' : '') ?> value="Decision Maker">Decision Maker</option>
          </select>
            </div>
          </div>

          <?php 
          $eu_designation = db_query("select * from designations");
          //$eu_data=db_fetch_array($eu_designation);

          foreach($eu_designation as $designation_data){
            $des_data[] = $designation_data['name'];
          }
         // print_r($des_data);
          ?>

          <div class="col-md-4">
            <div class="form-group">
          <label for="example-search-input">Designation<span class="text-danger">*</span>&nbsp;<?= (in_array($row['eu_designation'],$des_data))?'<i class="fa fa-check-circle green-color " ></i>':'<i class="fa fa-times-circle red-color " ></i>' ?></label>
          <select name="designation" class="form-control" placeholder="" aria-invalid="false" required>
              <option value="">---Select---</option>

              <?php $eu_designation = db_query("select * from designations");
              while($data=db_fetch_array($eu_designation)){ ?>

              <option value="<?= $data['name']?>" <?= ($row['eu_designation'] == $data['name'])?'selected':''?>><?= $data['name']?></option>
              <?php } ?>
          </select>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Type of validation<span class="text-danger">*</span></label>
              <select name="validation_type" class="form-control" required id="profiling_type" data-validation-required-message="This field is required">
              <option value="">Type of validation</option>
              <option value="profiling_validation" <?= (($row['validation_type']=='profiling_validation')?'selected':'') ?>>Validation through call (Profiling)</option>
              <option value="emailer_validation" <?= (($row['validation_type']=='emailer_validation')?'selected':'') ?> >Validation through emailer</option>
              </select>
            </div>
          </div>

          <div id="attachment_user"  style="display:none">
          <div class="col-lg-4 mb-3">

<label for="example-text-input">Attachment<span class="text-danger">*</span><br>(Max: 4MB)</label>
<input type="file" name="user_attachment" class="btn btn-default" value="<?= $user_attachement ?>" aria-invalid="false"/><?php if ($user_attachement) { ?><img src="<?= $user_attachement ?>" style="width:50px; height:50px" /><?php } ?>
</div>
              
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Reason to initiate LC Call<span class="text-danger">*</span></label>
              <textarea required minlength="50" value="" name="initiate_reason" class="form-control" placeholder=""></textarea>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">How Usage Confirmed</label>
              <select id="multiselect1" name="usage_confirmed[]" class="form-control" multiple>
                 
                  <option value="Visit" <?= $usage_confirmed == 'Visit' ? 'selected' : '' ?>>Visit</option>
                  <option value="Need More Validation" <?= $usage_confirmed == 'Need More Validation' ? 'selected' : '' ?>>Profiling/Validation Call</option>
                  <option value="Web search" <?= $usage_confirmed == 'Web search' ? 'selected' : '' ?>>Web search</option>
                  <option value="Job opening" <?= $usage_confirmed == 'Job opening' ? 'selected' : '' ?>>Job opening</option>
                  <option value="Existing Customer" <?= $usage_confirmed == 'Existing Customer' ? 'selected' : '' ?>>Existing Customer</option>
                </select>
            </div>
          </div>

          <div class="col-md-12">
              <div class="form-group">
                <label for="example-text-input">Visit Done</label>
                <input name="visit_done" value="Yes" type="radio" required id="mmd_win" class="filled-in radio-col-blue" <?= (($visit_done == 'Windows') ? 'checked' : '') ?>>
                <label for="mmd_win">Yes</label>
                <input name="visit_done" value="No" type="radio" required id="mmd_mac" class="filled-in radio-col-blue" <?= (($visit_done == 'Mac') ? 'checked' : '') ?>>
                <label for="mmd_mac">No</label>
              </div>
            </div>
        
        </div>

        <input type="hidden" name="pid" value="<?= $_POST['id'] ?>" />
        <input type="hidden" name="title" value="<?= $_POST['title'] ?>" />
        <input type="hidden" name="company_name" value="<?= $_POST['company_name'] ?>" />
        <input type="hidden" name="submitted_by" value="<?= $_POST['submitted_by'] ?>" />
        <input type="hidden" name="sender_type" value="<?= $_POST['sender_type'] ?>" />
        <input type="hidden" name="partner_name" value="<?= $_POST['partner_name'] ?>" />
        <input type="hidden" name="sender_id" value="<?= $_POST['sender_id'] ?>" />
        <input type="hidden" name="receiver_id" value="<?= $_POST['receiver_id'] ?>" />


        <div class="modal-footer">
          <input type="submit" name="save_notification" value="Submit" class="btn btn-primary" />
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

        </div>
      </form>
    </div>


  </div>
</div>

<script>
$(document).ready(function () {
    $('#multiselect1').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });
});

$(document).ready(function () {
  $('#profiling_type').on('change', function() {  

    var val_type = $('#profiling_type').val();
    if(val_type == 'emailer_validation'){
      
      // $("#attachment_user").append('<div class="col-lg-4 mb-3"><label for="example-text-input">Attachment<span class="text-danger">*</span><br>(Max: 4MB)</label><input type="file" name="emailer_attachment" class="btn btn-default" value="<?= $user_attachement ?>" required aria-invalid="false" title="Emailer response should be attached in word file" /><?php if ($user_attachement) { ?><img src="<?= $user_attachement ?>" style="width:50px; height:50px" /><?php } ?></div>');   
      $("#attachment_user").show(); 

    }else{

      $("#attachment_user").hide();
    }
  });
});
</script>