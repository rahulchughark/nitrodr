<?php  include('includes/include.php');  
include_once('helpers/DataController.php');
$helper = new DataController;

$_POST['ids'] = intval($_POST['ids']); 
$_POST['pid'] = intval($_POST['pid']);

$checkRequired = getSingleResult("SELECT CASE WHEN lead_status IS NULL OR lead_status = '' THEN 'False' WHEN source IS NULL OR source = '' THEN 'False' WHEN billing_reseller IS NULL OR billing_reseller = '' THEN 'False' WHEN credit_reseller IS NULL OR credit_reseller = '' THEN 'False' WHEN school_name IS NULL OR school_name = '' THEN 'False' WHEN address IS NULL OR address = '' THEN 'False' WHEN state IS NULL OR state = '' THEN 'False' WHEN city IS NULL OR city = '' THEN 'False' WHEN region IS NULL OR region = '' THEN 'False' WHEN pincode IS NULL OR pincode = '' THEN 'False' WHEN contact IS NULL OR contact = '' THEN 'False' WHEN website IS NULL OR website = '' THEN 'False' WHEN school_email IS NULL OR school_email = '' THEN 'False' WHEN annual_fees IS NULL OR annual_fees = '' THEN 'False' WHEN eu_name IS NULL OR eu_name = '' THEN 'False' WHEN eu_mobile IS NULL OR eu_mobile = '' THEN 'False' WHEN eu_email IS NULL OR eu_email = '' THEN 'False' ELSE 'True' END AS status FROM orders WHERE id=".$_POST['pid']);

//  if ($checkRequired == 'False'){
//     redir("manage_orders.php?m=editlead", true);
//  }
?>   

<div class="modal-dialog modal-dialog-centered modal-lg"> 
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Change status for <?=getSingleresult("select school_name from orders where id=".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['pid']))?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
   </div>
    <div class="modal-body">
      <?php 
        $lead_status=getSingleresult("select lead_status from orders where id=".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['pid']));

       ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Lead Status</label>
             
             <!--  <select name="lead_status" class="form-control" required id="lead_status" placeholder="" 
              data-validation-required-message="This field is required">
                                                 <option value="">---Select---</option>
                                                 <option <?= (($lead_status == 'Raw Data') ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                 <option <?= (($lead_status == 'Validation') ? 'selected' : '') ?> value="Validation">Validation</option>
                                                 <option <?= (($lead_status == 'Contacted') ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                 <option <?= (($lead_status == 'Qualified') ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                 <option <?= (($lead_status == 'Unqualified') ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                 <option <?= (($lead_status == 'Duplicate') ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
              </select> -->

              <select name="lead_status" class="form-control" required id="lead_status" 
                data-validation-required-message="This field is required">
                  <option value="">---Select---</option>
                  <?php
                      // Make sure $lead_status is defined (from POST or DB)
                      $statuses = $helper->getAllLeadStatusNames(); // Already defined in another file
                      foreach ($statuses as $status) {
                          $selected = (isset($lead_status) && $lead_status == $status) ? 'selected' : '';
                          echo "<option value=\"$status\" $selected>$status</option>";
                      }
                  ?>
              </select>

             </div>
                                    </div>
                                    
                                </div>
                                
    <input type="hidden" name="pid" value="<?=$_POST['pid']?>"/>                           
    <div class="mt-3 text-center">
      <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="status_data('<?=intval($_POST['pid'])?>','<?=intval($_POST['ids'])?>')">Save</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>


</div>
</div>



<script>
$(function() {
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      forceParse: false,
      autoclose: !0

  });

});

function status_data(pid,ids)
{
 var statusVal= $('#lead_status').val();

    if(statusVal != 'Validation' && statusVal != 'Qualified'){
        change_status(statusVal,pid,ids);
    }else{
        check = '<?= $checkRequired ?>';
        if(check == 'False'){
            swal({
                title: "Error!",
                text: "Please edit lead and fill all mandatory fields to update status!",
                type: "error"
            }, function() {
                $('#myModal1').modal('hide');
                $('#leads').DataTable().ajax.reload();
            });        
        }else{
            change_status(statusVal,pid,ids);
        }
    }
 

}


</script>