<?php include "includes/include.php";

$currentYear = date("Y");
$academicYears = [];
for ($i = -1; $i <= 1; $i++) {
    $start = $currentYear + $i;
    $end = $start + 1;
    $academicYears[] = $start . "-" . $end;
}
?>
<div class="modal-dialog modal-dialog-centered modal-lg">

	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Change Stage for <?php echo getSingleresult(
       "select school_name from orders where id=" . $_POST["pid"]
   ); ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
		</div>
		<div class="modal-body">
			<form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
				<?php
    $current_stage = getSingleresult(
        "select stage from orders where id=" . $_POST["pid"]
    );
	$academic_year = getSingleresult(
        "select academic_year from orders where id=" . $_POST["pid"]
    );
    $add_comm = getSingleresult(
        "select add_comm from orders where id=" . $_POST["pid"]
    );
    $payment_status = getSingleresult(
        "select partial_payment from orders where id=" . $_POST["pid"]
    );
    $programStartDate = getSingleresult(
        "select program_start_date from orders where id=" . $_POST["pid"]
    );
    $program_initiation_date = getSingleresult(
        "select program_initiation_date from orders where id=" . $_POST["pid"]
    );
    $is_opportunity = getSingleresult(
        "select is_opportunity from orders where id=" . $_POST["pid"]
    );
    if ($add_comm == "Demo Arranged") {
        $demo_schedule = getSingleresult(
            "select demo_arranged_schedule from orders where id=" .
                $_POST["pid"]
        );
    } elseif ($add_comm == "Demo Rescheduled") {
        $demo_schedule = getSingleresult(
            "select demo_rescheduled_schedule from orders where id=" .
                $_POST["pid"]
        );
    } elseif ($add_comm == "Demo Completed") {
        $demo_schedule = getSingleresult(
            "select demo_completed_schedule from orders where id=" .
                $_POST["pid"]
        );
    }

    $product_stage = db_query(
        "select distinct(form_id) from tbl_lead_product where lead_id=" .
            $_POST["pid"]
    );
    $p_stage = db_fetch_array($product_stage);

    $id = "'" . $_POST["pid"] . "'";
    ?>

				<div class="col-md-12 p-0">
            <div class="form-group">
              <label for="example-text-input">Stage<span class="text-danger">*</span></label>
							<select class="form-control" name="stage" id="dd_stage" onchange="select_stage(this.value,<?php echo $id; ?>)">
								<option value="" >---Select---</option>';
								<?php
        if ($i == 1) {
            $sqlStage = "select * from stages where 1 ";
            $stageList = db_query($sqlStage);
        } else {
            $sqlStage = "select * from stages";
            $stageList = db_query($sqlStage);
        }

        while ($row = db_fetch_array($stageList)) { ?>
									<option value="<?php echo $row["stage_name"]; ?>" <?php echo $row[
    "stage_name"
] == $current_stage
    ? "selected"
    : ""; ?>><?php echo $row["stage_name"]; ?></option>
								<?php }
        ?>
							</select>
			</div>
			
				</div>


					<div id="add_comment"<?php if (!getSingleresult("select count(id) from sub_stage where stage_name='" .
                 $current_stage ."'")) { ?> style="display:none"<?php } ?>>
					<div class="col-md-12 p-0">
            <div class="form-group">
              <label for="example-text-input">Sub Stage<span class="text-danger">*</span></label>
						<select id="add_comment_dd" onchange="payment_option(this.value,<?php echo $id; ?>)" name="add_comm" class="form-control" required="" />
								<?php
        $sstage_sql = db_query(
            "select * from sub_stage where stage_name='" . $current_stage . "'"
        );

        while ($sstage_data = db_fetch_array($sstage_sql)) { ?>
									<option <?php echo $add_comm == $sstage_data["name"]
             ? "selected"
             : ""; ?> value="<?php echo $sstage_data[
     "name"
 ]; ?>"><?php echo $sstage_data["name"]; ?></option>
								<?php }
        ?>
							</select>
			</div>
			

			</div>
        <?php if (
            $add_comm == "Demo Arranged" ||
            $add_comm == "Demo Rescheduled" ||
            $add_comm == "Demo Completed"
        ) { ?>
			<div id="demo_datetime_div">
				<div class="col-md-12 p-0">
					<div class="form-group">
						<label for="demo_datetime">Demo Schedule Date & Time<span class="text-danger">*</span></label>
						<input type="datetime-local" name="demo_datetime" id="demo_datetime" class="form-control" value="<?= $demo_schedule ?>" required>
					</div>
				</div>
			</div>
		<?php } elseif ($current_stage == "Demo") { ?>
			<div id="demo_datetime_div"  style="display:none;">
				<div class="col-md-12 p-0">
					<div class="form-group">
						<label for="demo_datetime">Demo Schedule Date & Time<span class="text-danger">*</span></label>
						<input type="datetime-local" name="demo_datetime" id="demo_datetime" class="form-control" value="<?= $demo_schedule ?>" required>
					</div>
				</div>
			</div>
	<?php } ?>

 </div>

 <?php if(isset($_POST['isOpportunity']) && $_POST['isOpportunity'] ){ ?>
 	<div class="form-group" id="academic_year_div">
					<label>Academic Year <span class="text-danger">*</span></label>
					<select name="academic_year" id="academic_year" class="form-control">
						<option value="">--- Select Academic Year ---</option>
						<?php foreach ($academicYears as $year) { ?>
							<option value="<?= $year ?>" <?= ($year == $academic_year) ? 'selected' : '' ?>><?= $year ?></option>
						<?php } ?>
					</select>
			</div>
<?php } ?>

 			<!-- <div id="program_start_dateDiv"  style="display:none;">
				<div class="col-md-12 p-0">
					<div class="form-group">
						<label for="program_start_date">Program Start date for Current Opportunity<span class="text-danger">*</span></label>
						<input type="date" name="program_start_date" id="program_start_date" class="form-control">
					</div>
				</div>
			</div> -->
 <div id="payment_status_div"                              <?php if (
     !getSingleresult("select partial_payment from orders where id=" . $id) ||
     $add_comm != "Partial/Credit"
 ) { ?> style="display:none"<?php } ?>>
					<div class="col-md-12 p-0">
            <div class="form-group">
              <label for="example-text-input">Payment Status<span class="text-danger">*</span></label>
						<select id="payment_status" name="payment_status" class="form-control" required="" />
									<option <?php echo $payment_status == "50% Advance"
             ? "selected"
             : ""; ?> value="50% Advance">50% Advance</option>
									<option <?php echo $payment_status == "30 Days Credit"
             ? "selected"
             : ""; ?> value="30 Days Credit">30 Days Credit</option>
									<option <?php echo $payment_status == "60 Days Credit"
             ? "selected"
             : ""; ?> value="60 Days Credit">60 Days Credit</option>
									<option <?php echo $payment_status == "90 Days Credit"
             ? "selected"
             : ""; ?> value="90 Days Credit">90 Days Credit</option>
							</select>
			</div>
			</div>
 </div>


				<input type="hidden" name="pid" value="<?php echo $_POST["pid"]; ?>" />

				<div class="mt-3 text-center">
					<button type="button" id="save_button" class="btn btn-primary" onclick="get_change_data('<?php echo $_POST[
         "pid"
     ]; ?>','<?php echo $_POST["ids"]; ?>')">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				</div>
			</form>
		</div>



<?php if (
    $_SESSION["user_type"] == "USR" ||
    $_SESSION["user_type"] == "PUSR"
) { ?>

	<script>
		function select_stage(a, id) {
			
			var page_access = '<?php echo $_POST["page_access"]
       ? $_POST["page_access"]
       : ""; ?>';
			var is_opportunity = '<?php echo $is_opportunity; ?>';
			// if(a == 'Billing' && is_opportunity ==1){
			// 	var programStartDate = '<?php echo $programStartDate; ?>';
			// 	var program_initiation_date = '<?php echo $program_initiation_date; ?>';
			// 	if(program_initiation_date=='' || program_initiation_date==null){
			// 		if(programStartDate == '' || programStartDate ==  null)
			// 		{
			// 			document.getElementById("program_start_dateDiv").style.display = "block";                
			// 		}
			// 	}else if((program_initiation_date != '' || program_initiation_date !=  null) && (programStartDate == '' || programStartDate ==  null)){
			// 		saveProgramDate(program_initiation_date,id);
			// 	} else{
			// 		document.getElementById("program_start_dateDiv").style.display = "none";
			// 	}
			// }
				$("#save_button").prop('disabled', false);
				$("#op").hide();
				$("#pay_tab").hide();
				$('#add_Pcomment').hide();
				$('#payment_status_div').hide();

				// Show / Hide Academic Year
				if (a === 'PO/CIF Issued') {
					$('#academic_year_div').show();
					$('#academic_year').attr('required', true);
				} else {
					$('#academic_year_div').hide();
					$('#academic_year').val('');
					$('#academic_year').removeAttr('required');
				}


				if(a == 'PO/Pymt/CIF'){
					$('#trust_div').show();
					$('#trust_name').attr("require",true);
				}else{
					$('#trust_div').hide();
					$('#trust_name').attr("require",false);
				}
				if (a) {
					$.ajax({
						type: 'POST',
						url: 'get_sub_stage.php',
						data: {
							stage: a,
							id: id,
							_ajax:1
						},
						success: function(html) {
							//alert(html);
							if (html != 'html') {
								$('#hidden_parallel_stage option:selected').remove();
                                $('#add_Pcomment_dd option:selected').remove();
								$('#add_comment').html(html);
								$('#add_comment').show();
							} else {
								$('#add_comment').hide();
								$('#hidden_parallel_stage option:selected').remove();
                                $('#add_Pcomment_dd option:selected').remove();
							}
						},
						error: function () {
						   Swal.fire("Error", "There was an error: " + data?.message, "error");
						}
					});
				}
		}
	</script>
<?php } else { ?>
	<script>
		function select_stage(a, id) {
			console.log("a",a);
			var is_opportunity = '<?php echo $is_opportunity; ?>';
			// if(a == 'Billing' && is_opportunity ==1){
			// 	var programStartDate = '<?php echo $programStartDate; ?>';
			// 	var program_initiation_date = '<?php echo $program_initiation_date; ?>';
			// 	if(program_initiation_date=='' || program_initiation_date==null){
			// 		if(programStartDate == '' || programStartDate ==  null)
			// 		{
			// 			document.getElementById("program_start_dateDiv").style.display = "block";                
			// 		}
			// 	}else if((program_initiation_date != '' || program_initiation_date !=  null) && (programStartDate == '' || programStartDate ==  null)){
			// 		saveProgramDate(program_initiation_date,id);
			// 	}else{
			// 		document.getElementById("program_start_dateDiv").style.display = "none";
			// 	}
			// }
			$("#op").hide();
			$("#pay_tab").hide();
			$('#add_Pcomment').hide();
			$('#payment_status_div').hide();

			// Show / Hide Academic Year
				if (a === 'PO/CIF Issued') {
					console.log("hi ra");
					$('#academic_year_div').show();
					$('#academic_year').attr('required', true);
				} else {
					$('#academic_year_div').hide();
					$('#academic_year').val('');
					$('#academic_year').removeAttr('required');
				}


			if(a == 'PO/Pymt/CIF'){
					$('#trust_div').show();
					$('#trust_name').attr("required", "true");
				}else{
					$('#trust_div').hide();
					$('#trust_name').attr("required", "false");
				}
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
							$('#hidden_parallel_stage option:selected').remove();
                            $('#add_Pcomment_dd option:selected').remove();
							$('#add_comment').html(html);
							$('#add_comment').show();
						} else {
							$('#hidden_parallel_stage option:selected').remove();
                            $('#add_Pcomment_dd option:selected').remove();
							$('#add_comment').hide();
						}
					}
				});
			}
		}

	</script>
<?php } ?>
<script>


	function payment_option(val,id) {
		if(val == 'Demo Arranged' || val == 'Demo Rescheduled' || val == 'Demo Completed')
        {
                document.getElementById("demo_datetime_div").style.display = "block";                
        }else{
                document.getElementById("demo_datetime_div").style.display = "none";
        }
		if(val!='Partial/Credit'){
			$('#payment_status_div').hide();
		}
	}


	function get_change_data(pid, ids) {
		var stage = $('#dd_stage :selected').text();
		var stagevalue = $('#dd_stage :selected').val();
		var substage = $('#add_comment_dd :selected').text();
		var substagevalue = $('#add_comment_dd :selected').val();
		var demo_datetime = $('#demo_datetime').val();
		var is_opportunity = '<?php echo $is_opportunity; ?>';
		var lead_idd = '<?php echo $is_opportunity; ?>';
		var academic_year = $('#academic_year').val();
		
		if(is_opportunity == 1 && stage == 'Billing')
		{
			// var programStartDate = '<?php echo $programStartDate; ?>';
			// if(programStartDate == '' || programStartDate ==  null)
			// {
			// 	var programStartDate = $('#program_start_date').val();
			// 	if (programStartDate  == '' || programStartDate ==  null) {
			// 					swal("Please Fill program start date.");
			// return false;
			// 	}
			// 	saveProgramDate(programStartDate,pid);
			// }

		}else if(substagevalue == 'Demo Arranged' || substagevalue == 'Demo Rescheduled' || substagevalue == 'Demo Completed'){
			if(demo_datetime == ''){
				swal("Please select Date Time.");
				return false;				
			}
		}
		if(stagevalue == ''){
			swal("Please select stage first.");
			return false;
		}
		if(substagevalue == ''){
			swal('Please select sub stage first');
			return false;
		}

		var payment_status = $('#payment_status :selected').val();
		if(substagevalue=='Partial/Credit'){

			if(payment_status == ''){
				swal("Please select payment status.");
				return false;
			}
		}
		// Get the files
		// if(stagevalue == 'PO/CIF Issued' && substagevalue == 'Approved PO Received'){
		// 	var files = $('#attachments')[0].files;
		// 	var attachments = [];
		// 	for (var i = 0; i < files.length; i++) {
		// 		attachments.push(files[i]);
		// 	}
		// }else{
			var attachments = [];
		// }
		// alert('hii')

		if (stagevalue === 'PO/CIF Issued' && academic_year === '') {
			swal("Please select Academic Year.");
			return false;
		}

		chage_stage(stage, pid, ids, substage, payment_status, attachments,demo_datetime, academic_year);

	}

	$(function() {
		var page_access = '<?php echo $_POST["page_access"]
      ? $_POST["page_access"]
      : ""; ?>';
                    var stage = $('#dd_stage').val();
                    console.log("stage value on load:", stage);

                    // Show / Hide Academic Year on page load
                    if (stage === 'PO/CIF Issued') {
                        $('#academic_year_div').show();
                        $('#academic_year').attr('required', true);
                    } else {
                        $('#academic_year_div').hide();
                        $('#academic_year').val('');
                        $('#academic_year').removeAttr('required');
                    }

                    var subStage = $('#add_comment_dd').val();
                    //alert(subStage);
                        var user_type = '<?php echo $_SESSION["user_type"]; ?>';
                        if(stage == 'EU PO Issued' && subStage=='Payment in Installments' && user_type=='MNGR'&& page_access == 'true')
                        {
                          $("#add_comment_dd").css("pointer-events","none");
                          $("#pay_tab").css("pointer-events","none");
                        }
                });

  $(function() {
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      forceParse: false,
      autoclose: !0


  });

});

function saveProgramDate(a,id) {
	$.ajax({
		type: 'POST',
		url: 'general_changes.php',
		data: {
			programStartDate: a,
			pid: id
		},
		success: function(res) {
		}
	});
}
</script>