<?php include('includes/include.php');

$data = db_query("select * from lapsed_orders where id=" . $_POST['id']);
?>


<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add as Fresh Lead&nbsp;<?= getSingleresult("select company_name from lapsed_orders where id=" . $_POST['id']) ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form action="view_lapsed.php" method="post" class="form p-t-20" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Lead Type<span class="text-danger">*</span></label>
              <div class="">
                <div class="form-check-inline my-1">
                  <div class="custom-control custom-radio">

                    <input name="lead_type" <?= (($lead_type == 'LC') ? 'checked' : '') ?> value="LC" type="radio" required id="md_checkbox_221" class="lead filled-in radio-col-red">
                    <label for="md_checkbox_221">LC</label>
                  </div>
                </div>
                <div class="form-check-inline my-1">
                  <div class="custom-control custom-radio">
                    <input name="lead_type" value="BD" type="radio" <?= (($lead_type == 'BD') ? 'checked' : '') ?> required id="md_checkbox_2121" class="lead filled-in radio-col-red">
                    <label for="md_checkbox_2121">BD</label>
                  </div>
                </div>
                <div class="form-check-inline my-1">
                  <div class="custom-control custom-radio">
                    <input name="lead_type" value="Incoming" <?= (($lead_type == 'Incoming') ? 'checked' : '') ?> type="radio" required id="md_checkbox_12121" class="lead filled-in radio-col-red">
                    <label for="md_checkbox_12121">Incoming</label>
                  </div>
                </div>
              </div>

              <div class="col-md-12" id="validation_type">
              <div class="form-group"></div>
              </div>

              <div class="col-md-12" id="attachment_user" style="display:none">
              <div class="form-group">
              <div class="col-lg-4 mb-3"><label for="example-text-input">Attachment<span class="text-danger">*</span><br>(Max: 4MB)</label><input type="file" name="user_attachment" class="btn btn-default" value="<?= $user_attachement ?>" aria-invalid="false" required/><?php if ($user_attachement) { ?><img src="<?= $user_attachement ?>" style="width:50px; height:50px" /><?php } ?></div>
              </div>
              </div>

              <div class="col-md-12" id="call_subject_profiling" style="display:none">
              <div class="form-group">
              <label for="example-text-input">Call Subject</label><span class="text-danger">*</span><select required id="subject" name="subject" class="form-control"><option value="Profiling Call">Profiling Call</option></select>
              </div>
              </div>
              
              <div class="col-md-12" id="remarks" style="display:none">
              <div class="form-group">
              <label for="example-text-input">Visit/Profiling Remarks</label><span class="text-danger">*</span><textarea required  value="" name="remarks" class="form-control" placeholder=""></textarea>
              </div>
              </div>

              <div class="col-md-12" id="call_subject_emailer" style="display:none">
              <div class="form-group">
              <label for="example-text-input">Call Subject</label><span class="text-danger">*</span><select required id="subject" name="subject" class="form-control"><option value="Profiling Call">Profiling Call</option></select>
              </div>
              </div>

              <div class="col-md-12" id="remarks_emailer" style="display:none">
              <div class="form-group">
              <label for="example-text-input">Visit/Profiling Remarks</label><span class="text-danger">*</span><textarea required  value="" name="remarks" class="form-control" placeholder=""></textarea>
              </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="example-text-input">Close Date<span class="text-danger">*</span></label>

                  <input type="text" value="<?= date('Y-m-t') ?>" readonly required="required" name="partner_close_date" class="form-control" id="datepicker-close-date" />

                </div>
              </div>
              <?php $os_runrate = db_fetch_array($data);
              if ($os_runrate['os'] == '') { ?>
              <div class="col-md-6">
              <div class="form-group">
                <label for="example-search-input" class=" ">OS<span class="text-danger">*</span></label><br>
                <div class="">
                  <div class="form-check-inline my-1">
                    <div class="custom-control custom-radio">

                      <input name="os" value="Windows" <?php echo ($os_runrate['os'] == 'Windows') ?  "checked" : "";  ?> type="radio" required id="customRadio5" class="custom-control-input">
                      <label class="custom-control-label" for="customRadio5">Windows</label>
                    </div>
                  </div>
                  <div class="form-check-inline my-1">
                    <div class="custom-control custom-radio">
                      <input name="os" value="Mac" <?php echo ($os_runrate['os'] == 'Mac') ?  "checked" : "";  ?> type="radio" required id="customRadio6" class="custom-control-input">
                      <label class="custom-control-label" for="customRadio6">Mac</label>
                    </div>
                    </div>
                  </div>
                </div>

                <?php } ?>
                </div>
            </div>
            <input type="hidden" name="lid" value="<?= $_POST['id'] ?>" />

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="lapsed_save" value="lapsed_save">Save</button>
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
      </form>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
$('.lead').on('change', function() {
  var leadID = $(this).val();
    // alert(leadID);
      if (leadID=='LC') {
          $.ajax({
              type: 'POST',
              url: 'ajaxlead_type_lapsed.php',
              data: {
                  lead_type_id: leadID,
                  lid : '<?= $_POST['id'] ?>'
                  },
              success: function(html) {
                  //alert(html);
                  $('#validation_type').html(html);  
                  $('#validation_type').show();                         
              }
          });
      }else{
            $('#validation_type').remove();  
            $('#attachment').remove();
            $("#attachment_user").remove();
            $("#call_subject_profiling").remove();
            $("#remarks").remove();
            $("#call_subject_emailer").remove();
            $("#remarks_emailer").remove();
        }
    });
});


  function select_stage(a) {
    $("#op").hide();
    $("#pay_tab").hide();
    if (a) {
      $.ajax({
        type: 'POST',
        url: 'get_sub_stage.php',
        data: 'stage=' + a,
        success: function(html) {
          //alert(html);
          if (html != 'html') {
            $('#add_comment').html(html);
            $('#add_comment').show();
          } else {
            $('#add_comment').hide();
          }
        }
      });
    }
  }

  function payment_option(val) {
    //alert(val);
    if (val == '100% Advance Received' || val == 'Payment Against Delivery') {
      $("#op").hide();
      $("#pay_tab").hide();
    } else if (val == 'Payment in Installments') {
      $("#pay_tab").hide();
      $("#op").hide();
    } else if (val == 'Payment Not Clear' || val == '') {
      //alert(12);
      $("#pay_tab").hide();
      ("#op").hide();
    }
  }


  function get_change_data(pid, ids) {
    var stage = $('#dd_stage :selected').text();
    var substage = $('#add_comment_dd :selected').text();
    //alert(substage);
    chage_stage(stage, pid, ids, substage);

  }

  $(function() {
    $('#datepicker-close-date').datepicker({
      format: 'yyyy-mm-dd',
      startDate: '-3d',
      autoclose: !0

    });

  });
</script>