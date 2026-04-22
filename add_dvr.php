<?php include('includes/header.php');?>
<?php 

     $license_type = $_GET['lead'];
     $sub_product_type =  $_GET['type'];
if($_POST['r_name'])
{
if($_FILES["user_attachment"])
{   
$target_dir = "uploads/";
$target_file = $target_dir .time(). basename($_FILES["user_attachment"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
if ($_FILES["user_attachment"]["size"] > 4000000) {
    echo "<script>alert('Sorry, your file is too large!')</script>";
    redir("add_leads.php",true);
    
}
else {
    move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
    
}
}  
    
    if($_POST['created_date'])
    {
    $created= $_POST['created_date'].' '.date('H:i:s');
    }
    
    $res=db_query("INSERT INTO `orders`(call_type,`r_name`, `r_email`, `r_user`,license_key,`source`, `lead_type`, `company_name`, `parent_company`, `landline`,region, `industry`,sub_industry, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `account_visited`, `visit_remarks`, `confirmation_from`, `license_type`, `quantity`, `created_by`, `team_id`, `status`,user_attachement,dvr_flag,is_dr,created_date,product_type,sub_product_type,association_name,website,license_end_date,data_ref) VALUES ('".htmlspecialchars($_POST['call_type'],ENT_QUOTES)."','".htmlspecialchars($_POST['r_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['r_email'], ENT_QUOTES)."','".htmlspecialchars($_POST['r_user'], ENT_QUOTES)."','".htmlspecialchars($_POST['license_key'], ENT_QUOTES)."','".htmlspecialchars($_POST['source'], ENT_QUOTES)."','','".htmlspecialchars($_POST['company_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['parent_company'], ENT_QUOTES)."','".htmlspecialchars($_POST['landline'], ENT_QUOTES)."','".htmlspecialchars($_POST['region'], ENT_QUOTES)."','".htmlspecialchars($_POST['industry'], ENT_QUOTES)."','".htmlspecialchars($_POST['sub_industry'], ENT_QUOTES)."','".htmlspecialchars($_POST['address'], ENT_QUOTES)."','".htmlspecialchars($_POST['pincode'], ENT_QUOTES)."','".htmlspecialchars($_POST['state'], ENT_QUOTES)."','".htmlspecialchars($_POST['city'], ENT_QUOTES)."','".htmlspecialchars($_POST['country'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_email'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_landline'], ENT_QUOTES)."','".htmlspecialchars($_POST['department'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_mobile'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_designation'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_role'], ENT_QUOTES)."','Yes','".htmlspecialchars($_POST['visit_remarks'], ENT_QUOTES)."','".htmlspecialchars($_POST['confirmation_from'], ENT_QUOTES)."','".htmlspecialchars($license_type, ENT_QUOTES)."','".htmlspecialchars($_POST['quantity'], ENT_QUOTES)."','".htmlspecialchars($_SESSION['user_id'], ENT_QUOTES)."','".htmlspecialchars($_SESSION['team_id'], ENT_QUOTES)."','Pending','".$target_file."',1,1,'".$created."','Subscription','".htmlspecialchars($sub_product_type, ENT_QUOTES)."','".htmlspecialchars($_POST['association_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['website'], ENT_QUOTES)."','".htmlspecialchars($_POST['license_end_date'], ENT_QUOTES)."',1)");
      
      

        if($res)
        {
            
            redir("dvr.php?add=success",true);
        }
}?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/ict-logo.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Add DVR</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add DVR</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">
                                <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id">
                                <div  class="add_lead">

                                        <div class=" form-group row" style="display:none;">
                                            <div class="col-md-4">
                                              
                                                <label class="control-label">Partner Name<span class="text-danger">*</span></label>
                                              
                                                    <input name="r_name" type="text" readonly value="<?=getSingleresult("select name from partners where id='".$_SESSION['team_id']."'")?>" class="form-control" placeholder="" required  >
                                            </div>
                                            <div class="col-md-4">
                                               
                                                <label class="control-label ">Partner Email<span class="text-danger">*</span></label>
                                                
                                                    <input readonly value="<?=$_SESSION['email']?>" name="r_email" type="email" required  class="form-control form-control" placeholder="" >
                                        </div>
                                            <div class="col-md-4">
                                                <label class="control-label ">Submited By<span class="text-danger">*</span></label>
                                                   
                                                <input name="r_user"  readonly value="<?=$_SESSION['name']?>" type="text" class="form-control" placeholder="" required  >
                                            
                                            </div>
                                        </div>



                                    <h5 class="card-subtitle">Customer Information</h5>

                                    <div class="row">

                                            <div class="col-lg-4 mb-3">
                                                <label class="control-label ">Date of Visit<span class="text-danger">*</span></label>
                                                <div class="calendar-field-with-icon">
                                                    <i class="fa fa-fw fa-calendar-week"></i>                                            
                                                    <input type="text" name="created_date" class="form-control" id="datetime" value="<?=date('Y-m-d')?>" />
                                                </div>                                                   
                                            </div>
                                             <div class="col-lg-4 mb-3">
                                              
                                                    <label class="control-label">Call Type<span class="text-danger">*</span></label>
                                                  
                                                    <?php $res=db_query("select * from call_type order by name asc"); 
                                                    
                                                    //print_r($res); die;
                                                    
                                                    ?>
                                                     <select name="call_type" id="call_type" class="form-control" required data-validation-required-message="This field is required">
                                                     <option value="" >---Select---</option>
                                                     <?php while($row=db_fetch_array($res))
                                                     { ?>
                                                 <option value='<?=$row['id']?>'><?=$row['name']?></option>
                                                     <?php } ?>
                                                     </select>
                                             </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Lead Source<span class="text-danger">*</span></label>

                                            <select name="source" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                    <?php $res = db_query("select * from lead_source where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                            
                                        </div>
                                        
                                        <div class="form-group row">

                                            <div class="col-lg-4 mb-3">
                                              
                                                    <label class="control-label ">Company Name<span class="text-danger">*</span></label>
                                                  
                                                        <input type="text" name="company_name" value="" class="form-control" placeholder="" required >
                                                 
                                            </div>

                                            <div class="col-lg-4 mb-3">
                                             
                                                    <label class="control-label ">Parent Company<span class="text-danger"></span></label>
                                                  
                                                        <input type="text" name="parent_company" value="" class="form-control" placeholder="">
                                               
                                            </div>

                                            <div class="col-lg-4 mb-3">
                                             
                                             <label class="control-label ">Association Name<span class="text-danger"></span></label>
                                           
                                                 <input type="text" name="association_name" value="" class="form-control" placeholder="">
                                        
                                            </div>
                                            <!--/span-->

                                            <div class="col-lg-4 mb-3">
                                              
                                              <label class="control-label ">Website<span class="text-danger">*</span></label>
                                            
                                                  <input type="text" name="website" value="<?=$website?>" class="form-control" placeholder="" required >
                                           
                                           </div>

                                            

                                        </div>
                                        
                                         <div class="form-group row">

                                            <!-- <div class="col-lg-4 mb-3">
                                              
                                              <label class="control-label ">Website<span class="text-danger">*</span></label>
                                            
                                                  <input type="text" name="website" value="" class="form-control" placeholder="" required >
                                           
                                           </div> -->

                                             <div class="col-lg-4 mb-3">
                                                    <label class="control-label">Landline Number<span class="text-danger"></span></label>
                                                   
                                                        <input type="number" min="0" name="landline" value="" class="form-control" placeholder="">
                                                  
                                            </div>
                                            <!--/span-->
                                           <div class="col-lg-4 mb-3">
                                              
                                                    <label class="control-label ">Region<span class="text-danger">*</span></label>
                                                   
                                                        <select name="region" class="form-control" placeholder="" required >
                                                        <option value="">---Select---</option>
                                                         <?php $res = db_query("select * from region where status=1");
                                                        while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (($region == $row['region']) ? 'selected' : '') ?> value="<?= $row['region'] ?>"><?= $row['region'] ?></option>
                                                        <?php } ?>
                                                        
                                                        </select>
                                                  
                                            <!--/span-->
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                               
                                                    <label class="control-label">Industry<span class="text-danger">*</span></label>
                                                 
                                                        <?php $res=db_query("select * from industry order by name ASC"); 
                                                    
                                                    //print_r($res); die;
                                                    
                                                    ?>
                                                     <select name="industry" id="industry" class="form-control" required >
                                                     <option value="">---Select---</option>
                                                     <?php while($row=db_fetch_array($res))
                                                     { ?>
                                                 <option value='<?=$row['id']?>'><?=$row['name']?></option>
                                                     <?php } ?>
                                                     </select>
                                                 
                                            </div>
                                        
                                    
                                          
                                        </div>
                                        <!--/row-->
                                        <div class="form-group row">

                                            <div class="col-lg-4 mb-3" id="sub_industry">
                                                
                                            </div>

                                             <div class="col-lg-4 mb-3">
                                               
                                                    <label class="control-label">Country<span class="text-danger">*</span></label>
                                                   
                                                        <input type="text" name="country" value="India" class="form-control" placeholder="" required readonly="">
                                                
                                            </div>
                                         
                                     
                                      
                                          <div class="col-lg-4 mb-3">
                                             
                                                    <label class="control-label">State<span class="text-danger">*</span></label>
                                                   
                                                    <?php $res=db_query("select * from states"); 
                                                    
                                                    //print_r($res); die;
                                                    
                                                    ?>
                                                     <select name="state" id="state" class="form-control" required >
                                                     <option value="" >---Select---</option>
                                                     <?php while($row=db_fetch_array($res))
                                                     { ?>
                                                 <option value='<?=$row['id']?>'><?=$row['name']?></option>
                                                     <?php } ?>
                                                     </select>
                                                   
                                            </div>
                                            
                                            <div class="col-lg-4 mb-3">
                                             
                                                    <label class="control-label ">City<span class="text-danger">*</span></label>
                                                  
                                                         <input type="text"   name="city" value="" class="form-control" placeholder="" required />
                                                   
                                            </div>
                                             <div class="col-lg-4 mb-3">
                                                
                                                    <label class="control-label ">Pin Code<span class="text-danger">*</span></label>
                                                  
                                                        <input type="number" min="0" name="pincode" value="" class="form-control" placeholder="" required />
                                                  
                                            </div>  
                                        
                                        <div class="col-md-6">
                                             
                                                    <label class="control-label">Address<span class="text-danger">*</span></label>
                                                
                                                        <textarea name="address" value="" rows="5" class="form-control" placeholder="" required ></textarea>
                                                  
                                            </div>

                                    </div>

                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Full Name<span class="text-danger">*</span></label>
                                                  
                                            <input name="eu_name" type="text"  value="" class="form-control" placeholder="" required >
                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Email<span class="text-danger">*</span></label>

                                             <input  value="" name="eu_email" type="email" required  class="form-control form-control" placeholder="">


                                        </div>


                                        <div class="col-lg-4 mb-3">

                                             <label class="control-label">Mobile<span class="text-danger">*</span></label>
                                                    
                                            <input type="number" min="0" name="eu_mobile" value="" class="form-control" placeholder="" required >

                                        </div>



                                    </div>



                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                         <label class="control-label">Landline Number<span class="text-danger"></span></label>
                                                  
                                         <input type="number" min="0" name="eu_landline" autocomplete="of" value="" class="form-control" placeholder="" >

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                          <label class="control-label ">Department<span class="text-danger"></span></label>
                                                  
                                          <input type="text" name="department" value="" class="form-control" placeholder="">

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Designation<span class="text-danger">*</span></label>
                                                    
                                            <input type="text" name="eu_designation" value="" class="form-control" placeholder="" required  />

                                            <!--/span-->
                                        </div>



                                    </div>



                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                             <label class="control-label">Role<span class="text-danger">*</span></label>
                                                  
                                             <select name="eu_role" class="form-control" placeholder="" required>
                                            <option value="">---Select---</option>
                                            <option value="User">User</option>
                                            <option value="Economy Buyer">Economy Buyer</option>
                                            <option value="Tech. Buyer">Tech. Buyer</option>
                                            <option value="Decision Maker">Decision Maker</option>


                                            </select>

                                        </div>


                                        <div class="col-lg-4 mb-3">
                                                
                                                    <label class="control-label">Visit Remarks<span class="text-danger">*</span></label>
                                                   
                                                        <textarea name="visit_remarks" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"></textarea>
                                                
                                        </div>


                                        <!-- <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Visit/Profiling Remarks<span class="text-danger">*</span></label>

                                            <textarea name="visit_remarks" value="" class="form-control" id="example-text-input" placeholder="Visit/Profiling Remarks" required data-validation-required-message="This field is required"><?= $visit_remarks ?></textarea>


                                        </div> -->
                                   
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label ">Usage Confirmation Received from<span class="text-danger">*</span></label>
                                                  
                                                       <select name="confirmation_from" class="form-control" placeholder="" required>
                                                        <option value="">---Select---</option>
                                                        <option value="Graphic Designer">Graphic Designer</option>
                                                        <option value="IT Manager">IT Manager</option>
                                                        <option value="IT Executive">IT Executive</option>
                                                        <option value="Reception">Reception</option>
                                                        <option value="Employee">Employee</option>
                                                        <option value="Customer Reference">Customer Reference</option>
                                                        <option value="Other">Other</option>
                                                         
                                                        
                                                        </select>
                                        </div>
                                        </div>
                                    <h5 class="card-subtitle">Lead Information</h5>

                                      <div class="row">

                                                <div class="col-lg-4 mb-3">
                                               <?php $license_type = ($license_type == 'Commercial')?"Subscription New":$license_type;?>
                                               <label class="control-label ">Type of License<span class="text-danger"></span></label>
                                             
                                                   <input type="text" name="license_type" value="<?=$license_type?>" class="form-control" placeholder="" readonly>
                                            
                                              </div>
                                               <?php if($sub_product_type != ''){ ?>             
                                              <div class="col-lg-4 mb-3">
                                               
                                               <label class="control-label ">Sub Product Type<span class="text-danger"></span></label>
                                             
                                                   <input type="text" name="license_type" value="<?=$sub_product_type?>" class="form-control" placeholder="" readonly>
                                            
                                              </div>
                                              <?php } ?>


                                        <div class="col-lg-4 mb-3">

                                         <label for="example-search-input" class=" ">Quantity<span class="text-danger">*</span></label><br>
                                            <div class="clearfix"> </div>

                                            <input type="text" name="quantity" id="range_qty" value="<?= ($quantity ? $quantity : 1) ?>">

                                        </div>
                                    </div>

                                    <div class="row">

                                          <div class="col-lg-4 mb-3">
                                         
                                         <label class="control-label">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>
                                      
                                             <input type="file" name="user_attachment" class="btn btn-default" /> 
                                      
                                          </div>

                                           <?php if($license_type == "Renewal") { 

                                             if($sub_product_type == "Migration"){ ?>

                                            <div class="col-lg-4 mb-3">
                                         
                                            <label class="control-label">License Key<span class="text-danger">*</span></label>
                                         
                                                <input type="text" required="required" name="license_key" class="form-control" /> 
                                                 
                                            </div> 

                                            <?php } ?>

                                          <div class="col-lg-4 mb-3">
                                         
                                            <label class="control-label">License End Date<span class="text-danger"></span></label>
                                         
                                                <input type="text" value="" required="required" name="license_end_date" id="license_end_date" class="form-control" /> 
                                                 
                                            </div>

                                        <?php } ?>


                                          
                                    </div>

                                </div>

                                <div class="button-items">

                                    <button type="submit" data-toggle="modal" data-target="#myModal" id="form_data" class="btn btn-primary  mt-2">Submit</button>

                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>

                                </div>
                            <!-- </form> -->
                        </div>
                   

                   </div>
                </div> <!-- end col -->


            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
   
    </form>
 
    <?php include('includes/footer.php') ?>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
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
         
     $(document).ready(function(){
            $('#check').click(function(){
             //alert($(this).is(':checked'));
                $(this).is(':checked') ? $('#pwd').attr('type', 'text') : $('#pwd').attr('type', 'password');
            });
        });
        $(document).ready(function() {
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2

         
    });
    $("#ex6").slider();
$("#ex6").on("slide", function(slideEvt) {
    $("#ex6SliderVal").text(slideEvt.value);
});

    </script>
    <script>
$(document).ready(function(){
    
     var wfheight = $(window).height();
                  
                  $('.add_lead_form').height(wfheight-215);
                  


      $('.add_lead_form').slimScroll({
        color: '#00f',
        size: '10px',
       height: 'auto',
       
      
    });
    
});
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

   function checkValue(res){
       if(res == 'Yes'){
           $('#visit_remarks_field').css('display','block');
           $('#visit_remarks').prop('required',true);
       }else{
           $('#visit_remarks_field').css('display','none');
           $('#visit_remarks').prop('required',false);
       }
   }   



        $(document).ready(function() {
            $("#range_qty").ionRangeSlider({
                skin: "flat",
                //type: "double",
                min: 1,
                //max: 100,
                // from:0
            })
        });

        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 265);
        });

        $(function() {

            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-3d',
                autoclose: !0
            });
        }); 


      $(function() {

            $('#datetime').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-7d',
                endDate: '0d',
                autoclose: !0
            });
        }); 

      $(function() {
            $('#license_end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: !0
            });
        }); 

</script>