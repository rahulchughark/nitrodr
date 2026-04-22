<?php include("includes/include.php"); ?>
<?php
// print_r($_POST);die;
if ($license == 'Renewal') {

        if (getSingleresult("select count(id) from sub_stage where stage_name='" . $_POST['stage'] . "' ")) { ?>
                 <div class="col-md-12 p-0">
            <div class="form-group">
              <label for="example-text-input">Sub Stage<span class="text-danger">*</span></label>
              <input type="hidden" id="hidden_sub_stage" name="sub_stage" value="">
                <select id="add_comment_dd" name="add_comm" onchange="payment_option(this.value)" class="form-control" required="" />
                                <option value="">--Select--</option>
                                <?php
                                $sstage_sql = db_query("select * from sub_stage where stage_name='" . $_POST['stage'] . "' order by name");
                                while ($sstage_data = db_fetch_array($sstage_sql)) {
                                ?>
                                        <option <?= (($add_comm == $sstage_data['name']) ? 'selected' : '') ?> value="<?= $sstage_data['name'] ?>"><?= $sstage_data['name'] ?></option>
                                <?php } ?>
                        </select>
            </div>
                 </div>
        <?php } else {
                "Nosub";
                exit;
        }
} else {
        $product_stage = db_query("select distinct(form_id) from tbl_lead_product where lead_id=" . $_POST['id']);
        $partial_payment = getSingleresult("select partial_payment from orders where id='" . $_POST['id'] . "'");
        $p_stage = db_fetch_array($product_stage);
        if ($p_stage['form_id'] == 1) {
                $sstage_sql = db_query("select * from sub_stage where stage_name='" . $_POST['stage'] . "' order by name");
        } else {
                $sstage_sql = db_query("select * from sub_stage where stage_name='" . $_POST['stage'] . "' order by name");
        }
        // print_r($sstage_sql);die;
        if (getSingleresult("select count(id) from sub_stage where stage_name='" . $_POST['stage'] . "'"))  { ?>
        
               <div class="col-md-12 p-0">
            <div class="form-group"> 
              <label for="example-text-input">Sub Stage<span class="text-danger">*</span></label>
                
                <input type="hidden" id="hidden_sub_stage" name="sub_stage" value="">
                        <select id="add_comment_dd" name="add_comm" class="form-control" onchange="paymentDD(this.value)" required="" />

                                <option value="">--Select--</option>
                                <?php
                                while ($sstage_data = db_fetch_array($sstage_sql)) {
                                ?>
                                <option value="<?= $sstage_data['name'] ?>"><?= $sstage_data['name'] ?></option>
                                <?php } ?>
                        </select>
            </div>
               </div>
<div id="demo_datetime_div" style="display:none;">
    <div class="col-md-12 p-0">
        <div class="form-group">
            <label for="demo_datetime">Demo Schedule Date & Time<span class="text-danger">*</span></label>
            <input type="datetime-local" name="demo_datetime" id="demo_datetime" class="form-control">
        </div>
    </div>
</div>
                               <div class="col-md-12 p-0" style="display:none" id="paymentStatusDiv">
					<div class="form-group">
						<label for="example-text-input">Payment Status<span class="text-danger">*</span></label>
                                        <select id="payment_status" name="payment_status" class="form-control" required="" />
						<option value="">Select payment status</option>
						<option value="50% Advance">50% Advance</option>
						<option value="30 Days Credit">30 Days Credit</option>
						<option value="60 Days Credit">60 Days Credit</option>
						<option value="90 Days Credit">90 Days Credit</option>
                                </select>
					</div>
				</div>
                                
                                <!-- <div class="col-md-12 p-0" id="attachment_div" style="display:none">
                                        <div class="form-group">
                                        <label for="attachments">Attachments<span class="text-danger">*</span></label>
                                        <input type="file" id="attachments" name="attachments[]" class="form-control" multiple />
                                        </div>
                                </div> -->

<?php }else if(($_POST['stage'] == 'Billing') &&  $partial_payment) {
?> 
                               <div class="col-md-12 p-0" id="paymentStatusDiv">
					<div class="form-group">
						<label for="example-text-input">Payment Status<span class="text-danger">*</span></label>
                                        <select class="form-control" required="" disabled id="payment_status" name="payment_status" />
						<option value="">No Advance Payment</option>
						<option value="50% Advance" <?= (($partial_payment == "50% Advance") ? 'selected' : '') ?>>50% Advance</option>
						<option value="30 Days Credit" <?= (($partial_payment == "30 Days Credit") ? 'selected' : '') ?>>30 Days Credit</option>
						<option value="60 Days Credit" <?= (($partial_payment == "60 Days Credit") ? 'selected' : '') ?>>60 Days Credit</option>
						<option value="90 Days Credit" <?= (($partial_payment == "90 Days Credit") ? 'selected' : '') ?>>90 Days Credit</option>
                                </select>
					</div>
				</div>

<?php 
} else {
        "Nosub";
        exit;
        }
}
?>
<script>

function paymentDD(a)
{
        if(a == 'Demo Arranged' || a == 'Demo Rescheduled' || a == 'Demo Completed')
        {
                document.getElementById("demo_datetime_div").style.display = "block";                
        }else{
                document.getElementById("demo_datetime_div").style.display = "none";
        }
        // if (a == 'Approved PO Received') {
	// 		$('#attachment_div').show();
	// 	} else {
	// 		$('#attachment_div').hide();
	// 	}

        if(a == 'Partial/Credit'){
                document.getElementById("paymentStatusDiv").style.display = "block";
        }else{
                document.getElementById("paymentStatusDiv").style.display = "none";
        }
}

</script>