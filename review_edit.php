<?php include('includes/include.php');

$product_stage = db_query("select distinct(form_id) from tbl_lead_product where lead_id=" . $_POST['pid']);
$p_stage = db_fetch_array($product_stage);
if ($p_stage['form_id'] == 1) {
	$i = 1;
} else {
	$i = 0;
}

$lead_data = db_query("select orders.*,lead_review.is_review from orders left join lead_review on orders.id=lead_review.lead_id where orders.id=". $_POST['pid']);
$lead_arr = db_fetch_array($lead_data);

?>
<div class="modal-dialog modal-dialog-centered">

	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Change Stage for <?= getSingleresult("select company_name from orders where id=" . $_POST['pid']) ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div class="modal-body">
			<form action="#" method="post" class="form p-t-20" name="leads">
				<?php
				$sqlStage = "select * from stages where 1";
				$stageList = db_query($sqlStage);


				?>
				<div class="row">
          <div class="col-md-12 p-0">
            <div class="form-group">
              <label for="example-text-input">Mark as In-Complete</label>
				<div class="col-md-9">		
				<input name="incomplete_check" value="yes" <?= (($lead_arr['is_review']==2)?'checked':'')?> type="checkbox" id="md_checkbox_21" class="filled-in check-col-pink">
				<label for="md_checkbox_21" style="margin-top: 20px;"></label>
				</div>	
				</div>
		  </div>

		  <div class="col-md-12 p-0">
            <div class="form-group">
			  <label for="example-text-input">Lead Type</label>
			  <select class="form-control" name="type_lead" id="type_lead">
                    <option value="">Change Lead Type</option>
                    <option value="LC" <?= (($lead_arr['lead_type']=='LC')?'selected':'')?>>LC</option>
                    <option value="BD" <?= (($lead_arr['lead_type']=='BD')?'selected':'')?>>BD</option>
                    <option value="Incoming" <?= (($lead_arr['lead_type']=='Incoming')?'selected':'')?>>Incoming</option>
               </select>
			</div>
		  </div>

		  <!-- <div class="col-md-12 p-0">
            <div class="form-group">
			  <label for="example-text-input">Caller Name</label>
			  <?php $res = db_query("select * from callers order by name ASC"); ?>
					<select name="caller" id="caller" class="form-control">
						<option value="">Select Caller</option>
						<?php while ($row = db_fetch_array($res)) { ?>
							<option <?= (($lead_arr['caller'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] . ' (' . $row['caller_id'] . ')' ?></option>
						<?php } ?>
					</select>
			</div>
		  </div> -->

          <div class="col-md-12 p-0">
            <div class="form-group">
              <label for="example-text-input">Stage</label>
						
							<select class="form-control" name="stage" id="dd_stage" onchange="select_stage(this.value,<?= $_POST['pid'] ?>)">
								<option value="">Select Stage</option>
								<?php
								if ($i == 1) {
									$sqlStage = "select * from stages where 1 order by stage_name ";
									$stageList = db_query($sqlStage);
								} else {
									$sqlStage = "select * from stages where 1 and is_parallel=0 order by stage_name";
									$stageList = db_query($sqlStage);
								}
								while ($row = db_fetch_array($stageList)) { ?>
									<option value="<?= $row['stage_name'] ?>" <?= (($row['stage_name'] == $current_stage) ? 'selected' : '') ?>><?= $row['stage_name'] ?></option>
								<?php } ?>
							</select>
						
					</div>
		  </div>
		  
		  <div class="col-md-12 p-0">
		  <div class="form-group">
					<div id="add_comment" <?php if (!getSingleresult("select count(id) from sub_stage where stage_name='" . $current_stage . "'")) { ?> style="display:none" <?php } ?>>
					
           
					<label for="example-text-input" >Sub Stage</label>
						<select id="add_comment_dd" name="add_comm" class="form-control">
								<option value="">--Select--</option>
								<?php
								if ($i == 1) {
									$sstage_sql = db_query("select * from sub_stage where stage_name='" . $current_stage . "' order by name");
								} else {
									$sstage_sql = db_query("select * from sub_stage where is_parallel=0 and stage_name='" . $current_stage . "' order by name");
								}
								while ($sstage_data = db_fetch_array($sstage_sql)) {
								?>
									<option <?= (($add_comm == $sstage_data['name']) ? 'selected' : '') ?> value="<?= $sstage_data['name'] ?>"><?= $sstage_data['name'] ?></option>
								<?php } ?>
							</select>
						
					</div>
			</div>
		  </div>

					<div id="add_Pcomment">
						<?php if ($add_comm == 'Lost to competition') { ?>
							<td>List of Products</td>
							<td><select id="add_Pcomment_dd" name="add_Pcomm" class="form-control">
									<option value="" disabled>--Select--</option>
									<option value="Citrix" <?= $add_Parallelcomm == 'Citrix' ? 'selected' : '' ?>>Citrix</option>
									<option value="Vmware" <?= $add_Parallelcomm == 'Vmware' ? 'selected' : '' ?>>Vmware</option>
									<option value="Microsoft" <?= $add_Parallelcomm == 'Microsoft' ? 'selected' : '' ?>>Microsoft</option>
									<option value="Terminal Services Plus" <?= $add_Parallelcomm == 'Terminal Services Plus' ? 'selected' : '' ?>>Terminal Services Plus</option>
									<option value="Accops" <?= $add_Parallelcomm == 'Accops' ? 'selected' : '' ?>>Accops</option>

								</select>
							</td>
						<?php } ?>

					</div>
					<div id="op" <?php if (!$op_this_month) { ?> style="display:none" <?php } ?>>
						<td>Order Processing for this month</td>
						<td><input type="radio" name="op" value='Yes' <?= (($op_this_month == 'Yes') ? 'checked' : 'checked') ?> class="radio" id="opy" /><label for="opy">Yes</label><input <?= (($op_this_month == 'No') ? 'checked' : '') ?> type="radio" name="op" class="radio-col-red" value='No' id="opn" /><label for="opn">No</label></td>
					</div>
					<div id="pay_tab" <?php if ($data['payment_status'] != 'Payment in Installments') { ?> style="display:none" <?php } ?>>
					<label for="example-text-input">Installment Details</label>
						<?php
						$inst_query = db_query("select * from installment_details where type='Lead' and pid='" . $_POST['pid'] . "'");
						$inst_data = db_fetch_array($inst_query);

						?>
						<div class="col-md-12 p-0">
            <div class="form-group">
							<table style="clear: both; border:1px solid black !important" class="table table-bordered table-striped" width="100%">
								<tbody>
								<tr>
								<td>
                 <p><strong>Order Price:</strong></p></td>
                  <td>
                    <input type="number" autocomplete="off" value="<?= $inst_data['order_price'] ?>" class="form-control" min="0" name="order_price" id='order_price' />
					</td>
					</tr>
									<tr>
										<td>
											<p><strong>1<sup>st</sup> Installment Date</strong></p>
										</td>
										<td>
											<input type="text" autocomplete="off" value="<?= $inst_data['date1'] ?>" class="form-control datepicker" name="date1" id='date1' />
										</td>
										<td>
											<p><strong>2<sup>nd</sup> Installment Date</strong></p>
										</td>
										<td>
											<input type="text" autocomplete="off" value="<?= $inst_data['date2'] ?>" class="form-control datepicker" name="date2" id='date2' />
										</td>
									</tr>
									<tr>
										<td>
											<p><strong>Installment Amount</strong></p>
										</td>
										<td>
											<input type="number" autocomplete="off" value='<?= $inst_data['instalment1'] ?>' class="form-control" name="instalment1" min="0" />
										</td>
										<td>
											<p><strong>Installment Amount</strong></p>
										</td>
										<td>
											<input type="number" autocomplete="off" value='<?= $inst_data['instalment2'] ?>' class="form-control" name="instalment2" min="0" />
										</td>
									</tr>
									<tr>
										<td>
											<p><strong>3<sup>rd</sup> Installment Date</strong></p>
										</td>
										<td>
											<input type="text" autocomplete="off" value='<?= $inst_data['date3'] ?>' class="form-control datepicker" name="date3" id='date3' />
										</td>
										<td>
											<p><strong>4<sup>th</sup> Installment Date</strong></p>
										</td>
										<td>
											<input type="text" autocomplete="off" value='<?= $inst_data['date4'] ?>' class="form-control datepicker" name="date4" id='date4' />
										</td>
									</tr>
									<tr>
										<td>
											<p><strong>Installment Amount</strong></p>
										</td>
										<td>
											<input type="number" autocomplete="off" value='<?= $inst_data['instalment3'] ?>' class="form-control" name="instalment3" min="0" />
										</td>
										<td>
											<p><strong>Installment Amount</strong></p>
										</td>
										<td>
											<input type="number" autocomplete="off" value='<?= $inst_data['instalment4'] ?>' class="form-control" name="instalment4" min="0" />
										</td>
									</tr>
									<tr>
										<td>
											<p><strong>5<sup>th</sup> Installment Date</strong></p>
										</td>
										<td>
											<input type="text" autocomplete="off" value='<?= $inst_data['date5'] ?>' class="form-control datepicker" name="date5" id='date5' />
										</td>
										<td>
											<p><strong>6<sup>th</sup> Installment Date</strong></p>
										</td>
										<td>
											<input type="text" autocomplete="off" value='<?= $inst_data['date6'] ?>' class="form-control datepicker" name="date6" id='date6' />
										</td>
									</tr>
									<tr>
										<td>
											<p><strong>Installment Amount</strong></p>
										</td>
										<td>
											<input type="number" autocomplete="off" value='<?= $inst_data['installment5'] ?>' class="form-control" name="instalment5" min="0" />
										</td>
										<td>
											<p><strong>Installment Amount</strong></p>
										</td>
										<td>
											<input type="number" autocomplete="off" value='<?= $inst_data['installment6'] ?>' class="form-control" name="instalment6" min="0" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					</div>
					<div class="col-md-12 p-0">
            <div class="form-group">
			<label for="example-text-input">Comment</label>
				<textarea class="form-control" rows='4' cols='7' name="comment" id="comment" placeholder="Comment"></textarea>
			</div>
					</div>

				<input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />
				
				<div class="modal-footer">
					<input type="submit" value="Save" name="review_edit" class="btn btn-primary" />

					<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

				</div>

			</form>
		</div>


	</div>

</div>

<script>
	function select_stage(a, id) {
		$("#op").hide();
		$("#pay_tab").hide();
		if (a) {
			$.ajax({
				type: 'POST',
				url: 'get_sub_stage.php',
				data: {
					stage: a,
					id: id
				},
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

	$('#md_checkbox_21').click(function() {
		if ($(this).is(':checked')) {
			$("#dd_stage").prop("disabled", true);
			$("#comment").prop("disabled", true);
			$("#add_comment_dd").prop("disabled",true);
			$("#add_Pcomment_dd").prop("disabled",true);
			$("#op").prop("disabled",true);
			$("#pay_tab").prop("disabled",true);
		} else {
			$("#dd_stage").prop("disabled", false);
			$("#comment").prop("disabled", false);
			$("#add_comment_dd").prop("disabled",false);
			$("#add_Pcomment_dd").prop("disabled",false);
			$("#op").prop("disabled",false);
			$("#pay_tab").prop("disabled",false);
		}
	})

	$(function() {
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      forceParse: false,
      autoclose: !0

  });

});

</script>