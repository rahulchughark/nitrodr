<?php include('includes/header.php');admin_page();?>
<?php

if($_POST['eid'])
{
	$sql=db_query("update upgrade_leads set eu_address='".$_POST['eu_address']."',eu_contact='".$_POST['eu_contact']."',eu_designation='".$_POST['eu_designation']."',contact_email='".$_POST['contact_email']."',landline_number='".$_POST['landline_number']."',mobile_number='".$_POST['mobile_number']."',stage='".$_POST['stage']."',add_comment='".$_POST['add_comment']."',reseller='".$_POST['partner']."',comment='".$_POST['comment']."',quantity='".$_POST['quantity']."',native_lead='".$_POST['native_lead']."' where id=".$_POST['eid']);
 
 redir("upgrade_lead_admin.php?update=success",true);
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
				<?php  if($_REQUEST['id']) { $sql=db_query("select * from upgrade_leads where id=".$_REQUEST['id']);
				$data=db_fetch_array($sql);
				@extract($data);
				}
				else
				{
					redir("upgrade_lead_admin.php",true);
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
                                            <td>Submitted By</td>
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
                                            <td width="65%"><input type="text" name="eu_name" class="form-control" value="<?=$eu_name?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>End User Address</td>
                                            <td>
                                               <textarea class="form-control" name="eu_address"><?=$eu_address?> </textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>End User Contact</td>
                                            <td>
                                               <input type="text" name="eu_contact" class="form-control" value="<?=$eu_contact?>" />  </td>
                                        </tr>
										<tr>
                                            <td>Designation</td>
                                            <td>
                                              <input type="text" name="eu_designation" class="form-control" value=" <?=$eu_designation?> " /> 
                                            </td>
                                        </tr>
										<tr>
                                            <td>Contact License Email</td>
                                            <td>
                                                 <input type="text" name="contact_email" class="form-control" value="<?=$contact_email?> " />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Landline Number</td>
                                            <td>
                                                <input type="text" name="landline_number" class="form-control" value="<?=$landline_number?> " />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Mobile Number</td>
                                            <td>
											  <input type="text" name="mobile_number" class="form-control" value="<?=$mobile_number?> " />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Quantity</td>
                                            <td>
												<input type="text" name="quantity" class="form-control" value="<?=$quantity?>" />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Stage</td>
                                            <td>
											<?=(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage'])?>	
											</td>
                                        </tr>
										<tr id="payment" <?php if($stage!="EU PO Issued") { ?> style="display:none" <?php } ?>>
										<?php $payment_status=$data['payment_status']; ?>
                                            <td>Payment Status</td>
                                            <td>
												<?=$data['payment_status']?>
                                            </td>
                                        </tr>
									    <tr id="op" <?php if(!$op_this_month) { ?> style="display:none" <?php } ?>>
										<td>Order Processing for this month</td>
										<td><?=$data['op_this_month']?></td>
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
										 <?=$inst_data['date1']?> 
										</td>
										<td  >
										<p><strong>2<sup>nd</sup> Installment Date</strong></p>
										</td>
										<td  >
										<?=$inst_data['date2']?> 
										</td>
										</tr>
										<tr>
										<td  >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td  >
										 <?=$inst_data['instalment1']?>
										</td>
										<td >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td  >
										<?=$inst_data['instalment2']?>
										</td>
										</tr>
										<tr>
										<td >
										<p><strong>3<sup>rd</sup> Installment Date</strong></p>
										</td>
										<td  >
										 <?=$inst_data['date3']?> 
										</td>
										<td  >
										<p><strong>4<sup>th</sup> Installment Date</strong></p>
										</td>
										<td  >
										 <?=$inst_data['date4']?> 
										</td>
										</tr>
										<tr>
										<td >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td >
									  <?=$inst_data['instalment3']?> 
										</td>
										<td  >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td >
										<?=$inst_data['instalment4']?>
										</td>
										</tr>
										</tbody>
										</table>
										</td>
										</tr>
										<tr>
                                            <td>Native Reseller</td>
                                            <td>
												<input type="text" name="native_lead" class="form-control" value="<?=$native_lead?>" />
                                            </td>
                                        </tr>
										<tr>
                                            <td>Re-Assign to Reseller</td>
                                            <td>
												<?php $res=db_query("select * from partners"); 
													
													 
													
													?>
                                                     <select name="partner" id="partner" required class="form-control">
													 <option>---Select---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($row['id']==$reseller)?'Selected':'')?> value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select>
                                            </td>
                                        </tr>
<tr>
                                            <td>Additional Comments</td>
                                            <td>
											 <textarea class="form-control" name="comment"><?=$comment?></textarea>
									 
												
												 <?php		
												 $goal=db_query("select * from activity_log where pid='".$_GET['id']."' and activity_type='Upgrade' order by created_date desc");
											$count=mysqli_num_rows($goal);
											$i=$count; if($count){ echo  ' <br/>'; while($data=db_fetch_array($goal)) { ?>
												<?=$i.'. ['.date('d-m-Y H:i:s',strtotime($data['created_date'])).']: <b>'.$data['description'].'</b><br/>'?>
											<?php $i--; }  } 
											
											 
											
											?>
												
												</td>
                                        </tr>
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
	 $("#add_comment_dd").prop('required',true);
	}
	else
	{
		$( "#add_comment" ).hide();
	 $("#add_comment_dd").prop('required',false);
	}
}
$(document).ready(function(){
            $('#md_checkbox_21').click(function(){
              
                if($(this).is(':checked'))
				{
					 $( "#ltype" ).show();
	 $("#ltype_dd").prop('required',true);
				}
				else
				{
				 $( "#ltype" ).hide();
				 $("#ltype_dd").prop('required',false);	
				}
        });
});
 function view_activity(a)
{
	//event.preventDefault();
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
		</script>