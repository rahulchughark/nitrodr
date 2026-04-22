<?php include('includes/header.php');?>
<?php if($_POST['caller'])
{

        if($_FILES["call_attachment"])
        {	
        $target_dir = "uploads/recordings/";
        $call_file = $target_dir .time(). basename($_FILES["call_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($call_file,PATHINFO_EXTENSION));
            
        if ($_FILES["call_attachment"]["size"] > 500000000 && strtolower($imageFileType)!='wav' ) {
            echo "<script>alert('Sorry, your file is too large or format is not valid!')</script>";
            redir("add_call_quality.php",true);
            
        }
        else {
            move_uploaded_file($_FILES["call_attachment"]["tmp_name"], $call_file);
            
        }
        }

        if($_FILES["other_attachment"])
        {	
        $target_dir = "uploads/other/";
        $target_file = $target_dir .time(). basename($_FILES["other_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
        if ($_FILES["other_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("add_call_quality.php",true);
            
        }
        else {
            move_uploaded_file($_FILES["other_attachment"]["tmp_name"], $target_file);
            
        }
        }
        $total_score=$_POST['opening_closing']+$_POST['flow_tone']+$_POST['data_aquiring']+$_POST['detail_info']+$_POST['sfdc_update_email'];
        if(!$_POST['eid'])
       {  $res=db_query(" INSERT INTO `call_quality`(`caller`, `extension`, `call_type`, `profiling_call_type`, `company_name`, `industry`, `sub_industry`, `customer_phone`, `opening_closing`, `flow_tone`, `data_aquiring`, `detail_info`, `sfdc_update_email`, `total_score`, `good_points`, `improvement_required`, `overall_feedback`, `call_attachment`, `other_attachment`, `created_by`,created_date) VALUES ('".$_POST['caller']."','".$_POST['extension']."','".$_POST['call_type']."','".$_POST['profiling_call_type']."','".$_POST['company_name']."','".$_POST['industry']."','".$_POST['sub_industry']."','".$_POST['customer_phone']."','".$_POST['opening_closing']."','".$_POST['flow_tone']."','".$_POST['data_aquiring']."','".$_POST['detail_info']."','".$_POST['sfdc_update_email']."','".$total_score."','".$_POST['good_points']."','".$_POST['improvement_required']."','".$_POST['overall_feedback']."','".$call_file."','".$target_file."','".$_SESSION['user_id']."','".$_POST['created_date']."')");
        if($res)
		{
		 	
			redir("call_quality.php?add=success",true);
        }
	else
        {
           redir("call_quality.php?fail=ext",true);
        }
}

       else
       {
        $res=db_query("UPDATE `call_quality` set `caller`='".$_POST['caller']."', `extension`='".$_POST['extension']."', `call_type`='".$_POST['call_type']."', `profiling_call_type`='".$_POST['profiling_call_type']."', `company_name`='".$_POST['company_name']."', `industry`='".$_POST['industry']."', `sub_industry`='".$_POST['sub_industry']."', `customer_phone`='".$_POST['customer_phone']."', `opening_closing`='".$_POST['opening_closing']."', `flow_tone`='".$_POST['flow_tone']."', `data_aquiring`='".$_POST['data_aquiring']."', `detail_info`='".$_POST['detail_info']."', `sfdc_update_email`='".$_POST['sfdc_update_email']."', `total_score`='".$total_score."', `good_points`='".$_POST['good_points']."', `improvement_required`='".$_POST['improvement_required']."', `overall_feedback`='".$_POST['overall_feedback']."', `call_attachment`='".$call_file."', `other_attachment`='".$target_file."', created_date='".$_POST['created_date']."' where id='".$_POST['eid']."'"); 
        if($res)
		{
		 	
			redir("call_quality.php?update=success",true);
        }
	else
        {
           redir("call_quality.php?fail=ext",true);
        }
        }

}
      


if($id)
{
    $sql=db_query("select * from call_quality where id=".$id);
    $data=db_fetch_array($sql);
    @extract($data);
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/css/bootstrap-slider.min.css" />
<!-- ============================================================== -->
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
							
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Call Quality</li>
                        </ol>
                    </div>
                    
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-black"><?=($id?'Edit':'Add')?> Call Quality</h4>
                            </div>
                            <div class="card-body">
                                <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" >
                                    <div class="form-body">
                                                                                
                                        <h3 class="box-title">Call Information</h3>
                                        <hr class="m-t-0 m-b-40">
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Date<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <input type="text" class="datepicker form-control" name="created_date" value="<?=$created_date?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Caller Name<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                    <?php  
												 $res=db_query("select * from callers order by name ASC"); 
												 ?>
												 <select name="caller" id="caller" class="form-control" required data-validation-required-message="This field is required">
													 <option value="">---Select---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($caller==$row['id'])?'selected':'')?> value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select>
											    </div>
                                                </div>
                                            </div>
                                        </div>
										 <!--/row-->
                                        <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Extension<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                    <input type="number" name="extension" value="<?=$extension?>"   required class="form-control" data-validation-required-message="This field is required"/>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Company Name<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <input type="text" value="<?=$company_name?>" name="company_name" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                    </div>
                                                </div>
                                            </div>
                                             
                                            <!--/span-->
                                        </div>
										<div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Type of Call<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <select onchange="show_profile(this.value)" name="call_type"  required class="form-control" data-validation-required-message="This field is required" >
                                                    <option value="">---Select---</option>
                                                    <option <?=(($call_type=='LC Fresh Call')?'selected':'')?> value="LC Fresh Call">LC Fresh Call</option> 
                                                    <option  <?=(($call_type=='LC Follow-up Call')?'selected':'')?> value="LC Follow-up Call">LC Follow-up Call</option>
                                                    <option  <?=(($call_type=='Profiling Call')?'selected':'')?> value="Profiling Call">Profiling Call</option>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="col-md-6" <?php if($call_type!='Profiling Call') { ?> style="display:none" <?php } ?> id="pro_call">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Type of Profiling Call<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                    <select name="profiling_call_type"  class="form-control" id="profiling_call_type">
                                                    <option value=''>---Select---</option>
                                                    <option  <?=(($profiling_call_type=='Company Profiling')?'selected':'')?> value="Company Profiling">Company Profiling</option> 
                                                    <option <?=(($profiling_call_type=='Candidate Profiling')?'selected':'')?>  value="Candidate Profiling">Candidate Profiling</option>
                                                     
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            
                                            <!--/span-->
                                        </div>
										
										<div class="row">
										  <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Industry<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <?php $res=db_query("select * from industry order by name ASC"); 
													
													//print_r($res); die;
													
													?>
                                                     <select name="industry" id="industry" class="form-control" required data-validation-required-message="This field is required">
													 <option value="">---Select---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($industry==$row['id'])?'selected':'')?> value='<?=$row['id']?>'><?=$row['name']?></option>
													 <?php } ?>
													 </select>
                                                    </div>
                                                </div>
                                            </div>
											 <div class="col-md-6" id="sub_industry">
                                             <?php if($sub_industry) { $query = db_query("SELECT * FROM sub_industry WHERE industry_id = ".$industry."  ORDER BY name ASC");  $rowCount = mysqli_num_rows($query);  if($rowCount > 0){
												echo '  <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Sub Industry<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls"><select name="sub_industry" class="form-control" required data-validation-required-message="This field is required" id="subind">';
														while($row = db_fetch_array($query)){ ?>
												<option <?=(($sub_industry==$row['id'])?'selected':'')?> value="<?=$row['id']?>"><?=$row['name']?></option>
											<?php }
											echo '</select></div></div>';
											  }  } ?>
                                                
                                            </div>
										
										</div>
										 <div class="row">
                                         <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Mobile<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                         <input type="number" min="0" name="customer_phone" value="<?=$customer_phone?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                              
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                         
                                        <!--/row-->
										<h3 class="box-title">Call Quality</h3>
                                        <hr class="m-t-0 m-b-40">
										<div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Opening & Closing<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <select id="oc" onchange="calculate_score()"  name="opening_closing" value=""  required class="form-control" data-validation-required-message="This field is required" >
                                                    <option value="">---Select---</option>
                                                     <?php for($i=0;$i<=20;$i++)
                                                     { ?>
                                                        <option <?=(($i==$opening_closing)?'selected':'')?> value="<?=$i?>"><?=$i?></option>
                                                     <?php $i++; } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Flow of Call & Tone<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <select id="ft" onchange="calculate_score()"  name="flow_tone" value=""  required class="form-control" data-validation-required-message="This field is required" >
                                                    <option value="">---Select---</option>
                                                     <?php for($i=0;$i<=20;$i++)
                                                     { ?>
                                                        <option <?=(($i==$flow_tone)?'selected':'')?> value="<?=$i?>"><?=$i?></option>
                                                     <?php $i++; } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
										<div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Data Acquiring (Designation, Email)<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <select id="da" onchange="calculate_score()"  name="data_aquiring" value=""  required class="form-control" data-validation-required-message="This field is required" >
                                                    <option value="">---Select---</option>
                                                     <?php for($i=0;$i<=20;$i++)
                                                     { ?>
                                                        <option <?=(($i==$data_aquiring)?'selected':'')?> value="<?=$i?>"><?=$i?></option>
                                                     <?php $i++; } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Detailed Info (TAT, Internal Audit)<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <select id="di" onchange="calculate_score()" name="detail_info" value=""  required class="form-control" data-validation-required-message="This field is required" >
                                                    <option value="">---Select---</option>
                                                     <?php for($i=0;$i<=20;$i++)
                                                     { ?>
                                                        <option <?=(($i==$detail_info)?'selected':'')?> value="<?=$i?>"><?=$i?></option>
                                                     <?php $i++; } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">SFDC update, Mail Sent<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <select id="sfdc" onchange="calculate_score()" name="sfdc_update_email" value=""  required class="form-control" data-validation-required-message="This field is required" >
                                                    <option value="">---Select---</option>
                                                     <?php for($i=0;$i<=20;$i++)
                                                     { ?>
                                                        <option <?=(($i==$sfdc_update_email)?'selected':'')?> value="<?=$i?>"><?=$i?></option>
                                                     <?php $i++; } ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Final Score<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                    <div class='progress'>
    <div class='progress-bar bg-dark active progress-bar-striped' role='progressbar' id="pro_bar" style='width: <?=$total_score?>%;height:17px;' role='progressbar'><span id="pb_text" style="font-weight:900"><?=$total_score?></span></div>
    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                
                                            <!--/span-->
                                        </div>
                                         <hr class="m-t-0 m-b-40">
										 
										<div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Good Points Observed<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <textarea name="good_points" id="good_points" rows="10" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?=$good_points?></textarea>
															
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Improvement Required<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <textarea name="improvement_required" id="improvement_required" rows="10" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?=$improvement_required?></textarea>
															
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Over all Call Feebdack<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <textarea name="overall_feedback"  id="overall_feedback"  rows="10" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?=$overall_feedback?></textarea>
															
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">PDF Attachment<span class="text-danger"></span></label>
                                                    <div class="col-md-9 controls">
                                                        <input type="file" name="other_attachment" class="form-control" placeholder="" />
															
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Call Recording Attachment<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <input type="file" name="call_attachment" class="form-control" placeholder="" required data-validation-required-message="This field is required" />
															
                                                    </div>
                                                </div>
                                            </div-->
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                           
                                            <!--/span-->
                                           
                                            <!--/span-->
                                        </div>
										 
                                    <hr> 
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                    <input type="hidden" value="<?=$id?>" name="eid" />
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                        <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> </div>
                                        </div>
                                    </div>
                                </form>
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
            <!-- ============================================================== -->

<?php include('includes/footer.php') ?>
 <script src="js/validation.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
    <script>
    	$(document).ready(function(){
	 
	 
	$('#industry').on('change',function(){
		//alert("hi");
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'industry_id='+stateID,
                success:function(html){
					//alert(html);
                    $('#sub_industry').html(html);
                }
            }); 
        } 
    });	
	});
	
    </script>
	  <script>

function calculate_score()
{
    var final=0;
   var oc = $('#oc').val();
   var ft = $('#ft').val();
   var da = $('#da').val();
   var di = $('#di').val();
   var sfdc = $('#sfdc').val();
   if(oc)
   {
   final=parseInt(final)+parseInt(oc)
   }
   if(ft)
   {
   final=parseInt(final)+parseInt(ft)
   }
   if(da)
   {
   final=parseInt(final)+parseInt(da)
   }
   if(di)
   {
   final=parseInt(final)+parseInt(di)
   }
   if(sfdc)
   {
   final=parseInt(final)+parseInt(sfdc)
   }
   $("#pro_bar").animate({
    width: final+'%'
  });
  $("#pb_text").html(final+'%');
    
}

function show_profile(type)
{
    if(type=='Profiling Call')
    {
        $('#profiling_call_type').prop('required',true); 
        $('#pro_call').show();
       
         
    }
    else
    {
        $('#profiling_call_type').prop('required',false);
        $('#pro_call').hide();
    }

}
$(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": true,
     locale: {
      format: 'YYYY-MM-DD'
    },
//startDate: '2017-01-01',
 //autoUpdateInput: false,
        
    });
});

    </script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">


<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
    <script>
$(document).ready(function() {
      $('#good_points').summernote({
        toolbar: [
        //[groupname, [button list]]
 
        ['para', ['ul', 'ol', 'paragraph']],
 
    ]
      });
      $('#improvement_required').summernote({
        toolbar: [
        //[groupname, [button list]]
 
        ['para', ['ul', 'ol', 'paragraph']],
 
    ]
      });
      $('#overall_feedback').summernote({
        toolbar: [
        //[groupname, [button list]]
 
        ['para', ['ul', 'ol', 'paragraph']],
 
    ]
      });
    });
    </script>