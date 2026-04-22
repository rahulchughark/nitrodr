<?php include('includes/header.php');?>
<?php

if($_POST['eid'])
{
if($_SESSION['user_type']=='MNGR')
 $sql=db_query("update upgrade_leads set eu_address='".$_POST['eu_address']."',eu_contact='".$_POST['eu_contact']."',eu_designation='".$_POST['eu_designation']."',contact_email='".$_POST['contact_email']."',landline_number='".$_POST['landline_number']."',mobile_number='".$_POST['mobile_number']."',stage='".$_POST['stage']."',add_comment='".$_POST['add_comment']."',assigned_to='".$_POST['assigned_to']."',payment_status='".$_POST['payment_status']."',comment='".$_POST['comment']."' where id=".$_POST['eid']);
else
	$sql=db_query("update upgrade_leads set eu_address='".$_POST['eu_address']."',eu_contact='".$_POST['eu_contact']."',eu_designation='".$_POST['eu_designation']."',contact_email='".$_POST['contact_email']."',landline_number='".$_POST['landline_number']."',mobile_number='".$_POST['mobile_number']."',stage='".$_POST['stage']."',add_comment='".$_POST['add_comment']."',payment_status='".$_POST['payment_status']."',comment='".$_POST['comment']."' where id=".$_POST['eid']);

if($_POST['payment_status']=='Payment in Installments')
	{
		$ps=db_query("insert into installment_details (`pid`, `type`, `date1`, `instalment1`, `date2`, `instalment2`, `date3`, `instalment3`, `date4`, `instalment4`, `added_by`) values ('".$_POST['eid']."','Upgrade','".$_POST['date1']."','".$_POST['instalment1']."','".$_POST['date2']."','".$_POST['instalment2']."','".$_POST['date3']."','".$_POST['instalment3']."','".$_POST['date4']."','".$_POST['instalment4']."','".$_SESSION['user_id']."')");
	}
	else
	{
		$ps=db_query("update upgrade_leads set op_this_month='".$_POST['op']."' where id='".$_POST['eid']."'");
	}

 redir("update_leads_partner.php?update=success",true);
}
if($_POST['remarks'])
{
	$res=db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('".$_POST['pid']."','".htmlspecialchars($_POST['remarks'], ENT_QUOTES)."','Upgrade','".$_POST['call_subject']."','".$_SESSION['user_id']."',1)");
}
?>
<!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">View Upgrade Lead</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">View Upgrade Lead</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                        
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
				<?php  if($_REQUEST['id']) { $sql=db_query("select * from upgrade_leads where reseller='".$_SESSION['team_id']."' and id=".$_REQUEST['id']);
				$data=db_fetch_array($sql);
				@extract($data);
				}
				else
				{
					redir("update_leads_partner.php",true);
				}
				?>
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="display:none">
                            <div class="card-body">
                                <h4 class="card-title">Partner Info</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
									<form action="#" method="post" enctype="multipart/form-data"> 
                                        <tr>
                                            <td width="35%">Partner Name</td>
                                            <td width="65%"><?=$r_name?></td>
                                        </tr>
                                        <tr>
                                            <td>Partner Email</td>
                                            <td>
                                               <?=$r_email?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Submited By</td>
                                            <td>
                                              <?=$r_user?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Customer Information</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">End User</td>
                                            <td width="65%"><?=$eu_name?></td>
                                        </tr>
                                        <tr>
                                            <td>End User Address</td>
                                            <td>
                                                <textarea name="eu_address" class="form-control"><?=$eu_address?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>End User Contact</td>
                                            <td>
                                               <input type="text" name="eu_contact" value="<?=$eu_contact?>" class="form-control" /> </td>
                                        </tr>
										<tr>
                                            <td>Designation</td>
                                            <td>
                                              <input type="text" name="eu_designation" value="<?=$eu_designation?>" class="form-control" /> 
                                            </td>
                                        </tr>
										<tr>
                                            <td>Contact License Email</td>
                                            <td>
                                                <input type="text" name="contact_email" value="<?=$contact_email?>" class="form-control" />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Landline Number</td>
                                            <td>
                                               <input type="text" name="landline_number" value="<?=$landline_number?>" class="form-control" />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Mobile Number</td>
                                            <td>
											 <input type="text" name="mobile_number" value="<?=$mobile_number?>" class="form-control" />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Quantity</td>
                                            <td>
												<?=$quantity?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Stage</td>
                                            <td>
												<select name="stage"  onchange="chage_stage(this.value)" class="form-control">
												<option value="">--Select--</option>
												<option <?=(($stage=='Prospecting')?'selected':'')?> value="Prospecting">Prospecting</option>
												<option <?=(($stage=='Verification')?'selected':'')?> value="Verification">Verification</option>
												<option <?=(($stage=='Quote')?'selected':'')?> value="Quote">Quote</option>
												<option <?=(($stage=='Negotiation')?'selected':'')?> value="Negotiation">Negotiation</option>
												<option <?=(($stage=='Commit')?'selected':'')?> value="Commit">Commit</option>
												<option <?=(($stage=='EU PO Issued')?'selected':'')?> value="EU PO Issued">EU PO Issued</option>
												<option <?=(($stage=='Booking')?'selected':'')?> value="Booking">Booking</option>
												<option <?=(($stage=='OEM Billing')?'selected':'')?> value="OEM Billing">OEM Billing</option>
												<option <?=(($stage=='Closed Lost')?'selected':'')?> value="Closed Lost">Closed Lost</option>

												</select>
                                            </td>
                                        </tr>
										<tr id="add_comment" <?php if($stage!="Closed Lost") { ?> style="display:none" <?php } ?>>
                                            <td>Sub Stage</td>
                                            <td>
												<select  id="add_comment_dd" name="add_comment" class="form-control">
												<option value="">--Select--</option>
												<option <?=(($add_comment=='Budget Issues')?'selected':'')?> value="Budget Issues">Budget Issues</option>
												<option <?=(($add_comment=='Future Requirement')?'selected':'')?> value="Future Requirement">Future Requirement</option>
												<option <?=(($add_comment=='Lack of Product Support')?'selected':'')?> value="Lack of Product Support">Lack of Product Support</option>
												<option <?=(($add_comment=='Other')?'selected':'')?> value="Other">Other</option>
												<option <?=(($add_comment=='Price Services')?'selected':'')?> value="Price Services">Price Services</option>
												<option <?=(($add_comment=='Price Software')?'selected':'')?> value="Price Software">Price Software</option>
												<option <?=(($add_comment=='Received Declaration')?'selected':'')?> value="Received Declaration">Received Declaration</option>
												<option <?=(($add_comment=='Selling Process')?'selected':'')?> value="Selling Process">Selling Process</option>
												</select>
                                            </td>
                                        </tr>
										<tr id="payment" <?php if($stage!="EU PO Issued") { ?> style="display:none" <?php } ?>>
										
                                            <td>Payment Status<?=$payment_status?></td>
                                            <td>
												<select <?php if($data['payment_status']) { echo 'disabled'; }?>  onchange="payment_option(this.value)" id="payment_dd" name="payment_status" class="form-control">
												<option value="">--Select--</option>
												<option <?=(($payment_status=='100% Payment Received')?'selected':'')?> value="100% Payment Received">100% Payment Received</option>
												<option <?=(($payment_status=='Payment After Software Delivery')?'selected':'')?> value="Payment After Software Delivery">Payment After Software Delivery</option>
												<option <?=(($payment_status=='Payment in Installments')?'selected':'')?> value="Payment in Installments">Payment in Installments</option>
												<option <?=(($payment_status=='Payment Not Clear')?'selected':'')?> value="Payment Not Clear">Payment Not Clear</option>
												 
												</select>
                                            </td>
                                        </tr>
									    <tr id="op" <?php if(!$op_this_month) { ?> style="display:none" <?php } ?>>
										<td>Order Processing for this month</td>
										<td><input type="radio" name="op" value='Yes' <?=(($op_this_month=='Yes')?'checked':'checked')?> class="radio" id="opy"  /><label for="opy">Yes</label><input <?=(($op_this_month=='No')?'checked':'')?> type="radio" name="op" class="radio-col-red" value='No' id="opn"  /><label for="opn">No</label></td>
										</tr>
										  <tr id="pay_tab" <?php if($data['payment_status']!='Payment in Installments') { ?> style="display:none" <?php } ?>>
										<td>Installment Details</td>
										<?php
											$inst_query=db_query("select * from installment_details where type='Upgrade' and pid='".$_GET['id']."'");
											$inst_data=db_fetch_array($inst_query);

										?>
										<td><table  style="clear: both; border:1px solid black !important" class="table table-bordered table-striped" width="100%" >
										<tbody>
										<tr>
										<td  >
										<p><strong>1<sup>st</sup> Installment Date</strong></p>
										</td>
										<td>
										<input type="text" autocomplete="off" value="<?=$inst_data['date1']?>" class="form-control datepicker" name="date1" id='date1' />
										</td>
										<td  >
										<p><strong>2<sup>nd</sup> Installment Date</strong></p>
										</td>
										<td  >
										<input type="text" autocomplete="off" value="<?=$inst_data['date2']?>" class="form-control datepicker" name="date2" id='date2' />
										</td>
										</tr>
										<tr>
										<td  >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td  >
										 <input type="number" autocomplete="off" value='<?=$inst_data['instalment1']?>' class="form-control" name="instalment1" min="0" />
										</td>
										<td >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td  >
										<input type="number" autocomplete="off"  value='<?=$inst_data['instalment2']?>' class="form-control" name="instalment2" min="0" />
										</td>
										</tr>
										<tr>
										<td >
										<p><strong>3<sup>rd</sup> Installment Date</strong></p>
										</td>
										<td  >
										<input type="text" autocomplete="off" value='<?=$inst_data['date3']?>' class="form-control datepicker" name="date3" id='date3' />
										</td>
										<td  >
										<p><strong>4<sup>th</sup> Installment Date</strong></p>
										</td>
										<td  >
										<input type="text" autocomplete="off" value='<?=$inst_data['date4']?>' class="form-control datepicker" name="date4" id='date4' />
										</td>
										</tr>
										<tr>
										<td >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td >
										<input type="number" autocomplete="off" value=' <?=$inst_data['instalment3']?>' class="form-control" name="instalment3" min="0" />
										</td>
										<td  >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td >
										<input type="number" autocomplete="off"  value='<?=$inst_data['instalment4']?>' class="form-control" name="instalment4" min="0" />
										</td>
										</tr>
										</tbody>
										</table>
										</td>
										</tr>
										<tr>
                                            <td>Additional Comment</td>
                                            <td>
												<textarea class="form-control" name="comment"><?=$comment?></textarea>
												<button onclick="add_activity(<?=$_GET['id']?>)" class="btn btn-primary">Log a Call</button>&nbsp;<button onclick="view_activity(<?=$_GET['id']?>)" class="btn btn-inverse">View</button>
                                            </td>
                                        </tr>
										<?php if($_SESSION['user_type']=='MNGR') { ?> <tr>
                                            <td>Assign To</td>
                                            <td>
												 <select name="assigned_to" id="assigned_to" class="form-control">
												 <option value="" >---Select---</option>
													 
                                                      <?php 
                                                       $res=db_query("select * from users where team_id=".$_SESSION['team_id']); 
                                                          while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($assigned_to==$row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
                                                                                                         <?php }  ?>
                                                                                                         
                                                                                                         
													 </select>
                                            </td>
                                        </tr>
										<?php } ?>
											 <tr>
											 <input type="hidden" value="<?=$_GET['id']?>" name="eid" />
                                            <td colspan=2 ><button type="submit" onclick="" class="btn btn-primary">Save</button>
											</form>
											<button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse">Back</button>
											
											</td>
</tr>											
                                    </tbody>
                                </table>
                            </div>
                        </div>
						
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
<div id="myModal" class="modal fade" role="dialog">
  

</div>
<?php include('includes/footer.php') ?>
<script>
function chage_stage(a)
{
	if(a=='Closed Lost')
	{
	 $( "#add_comment" ).show();
	 $("#add_comment_dd").attr("required","required");
	 	$( "#payment" ).hide();
	 $("#payment_dd").removeAttr("required","required");
	}
	else if(a=='EU PO Issued')
	{
		$( "#add_comment" ).hide();
	 $("#add_comment_dd").removeAttr("required","required");
		$( "#payment" ).show();
	 $("#payment_dd").attr("required","required");
	}
	else
	{
		$( "#add_comment" ).hide();
	 $('#add_comment_dd').removeAttr("required","required");
	 	 	$( "#payment" ).hide();
	 $("#payment_dd").removeAttr("required","required");
	}
}

function payment_option(val)
{
	//alert(val);
if(val=='100% Payment Received' || val=='Payment After Software Delivery')
{
	$("#op").show();
	$("#pay_tab").hide();
}
else if(val=='Payment in Installments'){
$("#pay_tab").show();
$("#op").hide();
}
else if(val=='Payment Not Clear' || val=='')
{
	//alert(12);
	$("#pay_tab").hide();
	("#op").hide();	
}
}
 function add_activity(a)
{
	event.preventDefault() ;
	 $.ajax({  
    type: 'POST',  
    url: 'add_activity.php',
	data:{pid:a},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}	
 function view_activity(a)
{
	event.preventDefault();
	var type='Upgrade';
	 $.ajax({  
    type: 'POST',  
    url: 'view_activity.php',
	data:{pid:a,type:type},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}	
$(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": true,
	  minDate:new Date(),
     locale: {
      format: 'YYYY-MM-DD'
    },
	autoUpdateInput: false,
 
        
    }, function(start, end) {
	$(this.element).val(start.format('YYYY-MM-DD'));
	});
	
	
	
});
		</script>