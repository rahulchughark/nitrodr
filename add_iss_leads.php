<?php include('includes/header.php');
$agreement_type = $_GET['lead'];
?>

<?php if(isset($_POST['r_name']) && $_POST['r_name'] != '')
{
if(!empty($_FILES["user_attachment"]["name"]))
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
else{
    $target_file = '';
}
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";
//     print_r($_POST);die;
    if($_POST['other_board'] != '')
    {
       $school_board = $_POST['other_board'];
    }
    else
    {
       $school_board = $_POST['school_board'];
    }

    $current_date = date('Y-m-d H:i:s');

    if($_POST['partner'] != ''){
        $partner = getSingleresult("select name from partners where id='".$_POST['partner']."'");
        $teamId = $_POST['partner'];
      }else{
        $partner = $_POST['r_name'];
        $teamId = $_SESSION['team_id'];
      }
     
     if($_POST['allign_to'] != ''){
       $align_to = $_POST['allign_to'];
     }else{
       $align_to = 0;
      }

      $agreement_typeI = getSingleresult("select license_type from tbl_product_pivot where id = ".$_POST['product_typeIss']);
    
      $res=db_query("INSERT INTO `orders`(expected_close_date,school_board,`r_name`, `r_email`, `r_user`,`source`,`sub_lead_source`,`school_name`,`is_group`, `group_name`, `contact`,school_email,`address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `eu_mobile`, `eu_designation`, `quantity`, `created_by`, `team_id`, `status`,user_attachement,created_date,agreement_type,allign_to) VALUES ('".$_POST['expected_close_date']."','".$school_board."','".htmlspecialchars($_POST['r_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['r_email'], ENT_QUOTES)."','".htmlspecialchars($_POST['r_user'], ENT_QUOTES)."','".htmlspecialchars($_POST['source'], ENT_QUOTES)."','".htmlspecialchars($_POST['sub_lead_source'], ENT_QUOTES)."','".htmlspecialchars($_POST['school_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['is_group'], ENT_QUOTES)."','".htmlspecialchars($_POST['group_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['contact'], ENT_QUOTES)."','".htmlspecialchars($_POST['school_email'], ENT_QUOTES)."','".htmlspecialchars($_POST['address'], ENT_QUOTES)."','".htmlspecialchars($_POST['pincode'], ENT_QUOTES)."','".htmlspecialchars($_POST['state'], ENT_QUOTES)."','".htmlspecialchars($_POST['city'], ENT_QUOTES)."','India','".htmlspecialchars($_POST['eu_name'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_email'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_landline'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_mobile'], ENT_QUOTES)."','".htmlspecialchars($_POST['eu_designation'], ENT_QUOTES)."','".htmlspecialchars($_POST['quantity'], ENT_QUOTES)."','".htmlspecialchars($_SESSION['user_id'], ENT_QUOTES)."','".htmlspecialchars($_SESSION['team_id'], ENT_QUOTES)."','Pending','".$target_file."','".$current_date."','".$agreement_typeI."','".$align_to."')");

    $lead_id = get_insert_id();

    if ($_POST['e_name']) {
        $number = count($_POST["e_name"]);

        for ($i = 0; $i < $number; $i++) {
            $query =  insertLeadContact('tbl_lead_contact', $lead_id, $_POST['e_name'][$i], $_POST['e_email'][$i], $_POST['e_mobile'][$i], $_POST['e_designation'][$i]);
        }
    }
      
    $activity_log = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by) values ('" . $lead_id . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_type'] . "','" . $_SESSION['user_id'] . "')");

    $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' , ". $_POST['productIss']. " , " . $_POST['product_typeIss'] .",0,now())");
      

        if($res)
        {

            $product = getSingleresult("select product_name from tbl_product where id='" . $_POST['productIss'] . "'");
            $product_type = getSingleresult("select product_type from tbl_product_pivot where id='" . $_POST['product_typeIss'] . "'");

            $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id='" . $teamId . "' and status='Active'");
            $users2 = db_query("select email,name from users where id='" . $_POST['allign_to'] . "' and status='Active' ");
            $users = db_fetch_array($users2);
            $addTo[] = $users['email'];
            $addCc[] = $manager_email;

            $adminsEmail = db_query("select email from users where user_type IN ('SUPERADMIN','ADMIN')");
            while ($rowAd = db_fetch_array($adminsEmail)) {
                $addTo[] = $rowAd['email'];                
            }
            $operEmail = db_query("select email from users where user_type = 'OPERATIONS'");            
            while ($rowOp = db_fetch_array($operEmail)) {
                $addCc[] = $rowOp['email'];                
            }
      
              $setSubject = "New ISS Lead Added to DR Portal from " . $_POST['r_name'] . " (" . $_POST['r_user'] . ")";
              $body    = "Hi,<br><br> There is new ISS lead added to ICT DR Portal with details as below:-<br><br>
              <ul>
              <li><b>Partner Name</b> : " . $_POST['r_name'] . " </li>
              <li><b>Name</b> : " . $_POST['r_name'] . " </li>
              <li><b>Email</b> : " . $_POST['r_email'] . " </li>
              <li><b>Source</b> : " . $_POST['source'] . " </li>
              <li><b>Organization Name</b> : " . $_POST['school_name'] . " </li>
              <li><b>Product Name</b> : " . $product . " </li>
              <li><b>Product Type</b> : " . $product_type . " </li>
              <li><b>Address</b> : " . htmlspecialchars($_POST['address'], ENT_QUOTES) . " </li>
              <li><b>Mobile</b> : " . $_POST['eu_mobile'] . " </li>
              <li><b>Email</b> : " . $_POST['eu_email'] . " </li>
              <li><b>Agreement Type</b> : " . $agreement_typeE . " </li>
              <li><b>Quantity</b> : " . $_POST['quantity'] . " </li>
              <li><b>Expected Close Date</b> : " . $_POST['expected_close_date'] . " </li>
              <li><b>Call Subject</b> : " . $_POST['call_type'] . " </li>
              <li><b>Remarks</b> : " . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . " </li>
              </ul><br>
              Thanks,<br>
              ICT DR Portal";

              $addBcc[] = '';
              sendMail($addTo, $addCc, $addBcc, $setSubject, $body);

            if($_POST['agreement_type'] == 'Renewal'){
                redir("renewal_leads_partner.php?add=success",true);
            }else{
                redir("iss_leads.php?add=success",true);
            }
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

                                    <small class="text-muted">Home > Add Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Lead</h4>
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
                                             <div class="col-lg-3 mb-3">
                                              
                                              <label class="control-label ">Product Name<span class="text-danger">*</span></label>
                                            
                                              <select name="productIss" class="form-control" onchange id="productIss" required>
                                                <option value="">---Select---</option>
                                                <?php $res_product = selectProductPartner($_SESSION['team_id']);
                                                while ($row = db_fetch_array($res_product)) { ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['product_name']; ?></option>
                                                <?php }  ?>
                                            </select>
                                           
                                             </div>

                                             <div class="col-lg-3 mb-3" id="productTypeIss">

                                            </div>


     <div class="col-lg-3 mb-3">
                                              
                                              <label class="control-label ">School Board<span class="text-danger">*</span></label>
                                            
                                              <select name="school_board" id="school_board" class="form-control" placeholder="" required>
                                                <option value="">---Select---</option>
                                                <option value="CBSE">CBSE</option>
                                                <option value="ICSE">ICSE</option>
                                                <option value="IB">IB</option>
                                                <option value="IGCSE">IGCSE</option>
                                                <option value="STATE">STATE</option>
                                                <option value="Others">Others</option>
                                                </select>
                                           
                                             </div>

                                             <!-- <div class="col-lg-3 mb-3" id="other_board_div" style="display: none">
                                             <label class="control-label">Enter Board Name<span class="text-danger">*</span></label>
                                             <input type="text" id="other_board" name="other_board" value="" class="form-control" placeholder="" >
                                        </div> -->

                                            

                                            <div class="col-lg-3 mb-3">
                                                    
                                                <label class="control-label">Organization Name<span class="text-danger">*</span></label>
                                                
                                                <input type="text" name="school_name" pattern="[a-zA-Z'-'\s]*" value="" class="form-control" placeholder="" required>
                                                    
                                            </div>
                                            </div>
                                        
                                        <div class="form-group row">

                                            <div class="col-lg-3 mb-3">
                                              
                                                    <label class="control-label ">Is Group</label>
                                                  
                                                        <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder="" style="width: 18px;">
                                                    
                                            </div>

                                            <div class="col-lg-3 mb-3" id="group_name_div" style="display: none">
                                              
                                                    <label class="control-label ">Group Name<span class="text-danger">*</span></label>
                                                  
                                                        <input type="text" name="group_name" id="group_name" value="" class="form-control" placeholder="">
                                                    
                                            </div>
                                            <!--/span-->

                                            <div class="col-lg-3 mb-3">

                                                <label for="example-text-input" class="">Lead Source<span class="text-danger">*</span></label>

                                                <select name="source" id="lead_source" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                    <option value="">---Select---</option>
                                                        <?php $res = db_query("select * from lead_source where status=1");
                                                    while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                            <div class="col-lg-3 mb-3" id="sub_lead_source">
                                            <?php if ($sub_lead_source) {
                                                $query = db_query("SELECT * FROM sub_lead_source WHERE lead_source = " . $lead_source . "  ORDER BY sub_lead_source ASC");
                                                $rowCount = mysqli_num_rows($query);
                                                if ($rowCount > 0) {
                                                    echo '  
                                                    <label for="example-text-input">Sub Lead Source<span class="text-danger">*</span></label>
                                                    <select name="sub_lead_source" class="form-control" required data-validation-required-message="This field is required" id="subleadsource">';
                                                    while ($row = db_fetch_array($query)) {
                                                        echo '<option value="' . $row['sub_lead_source'] . '">' . $row['sub_lead_source'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                            } ?>
                                        </div>
                                        </div>
                                        
                                        <div class="form-group row">

                                        <!-- <div class="col-lg-3 mb-3">
                                                    
                                                    <label class="control-label ">Contact Number<span class="text-danger">*</span></label>
                                                    
                                                        <input type="text" minlength="11" maxlength="12" id="contact_num" name="contact" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required" onkeypress="return isNumberKey(event,this.id)">
                                                    
                                            </div> -->
                                            <!--/span-->

                                            <!-- <div class="col-lg-3 mb-3">
                                                
                                                    <label class="control-label ">Email Id<span class="text-danger">*</span></label>
                                                    
                                                        <input type="email" min="0" name="school_email" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required" >
                                                    
                                            </div> -->

                                            <div class="col-lg-3 mb-3">
                                             
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
                                            
                                            <div class="col-lg-3 mb-3" id="city">
                                            <label class="control-label">City<span class="text-danger">*</span></label>
                                               <select name="city" id="city" class="form-control" required >
                                                     <option value="" >---Select---</option>
                                                     
                                                </select>
                                                   
                                            </div>
                                    
                                          

                                      
                                          
                                             <div class="col-lg-3 mb-3">
                                                
                                                    <label class="control-label ">Pin Code<span class="text-danger">*</span></label>
                                                  
                                                        <input type="number" min="0" name="pincode" value="" class="form-control" placeholder="" required />
                                                  
                                            </div>  
                                        
                                        <div class="col-md-3">
                                             
                                                    <label class="control-label">Address<span class="text-danger">*</span></label>
                                                
                                                        <textarea name="address" value="" rows="5" class="form-control" placeholder="" required ></textarea>
                                                  
                                            </div>

                                    </div>

                                    <h5 class="card-subtitle">Decision Maker Information</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Full Name<span class="text-danger">*</span></label>
                                                  
                                            <input name="eu_name" type="text" pattern="[a-zA-Z'-'\s]*"  value="" class="form-control" placeholder="" required >
                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Email<span class="text-danger">*</span></label>

                                             <input  value="" name="eu_email" type="email" required  class="form-control form-control" placeholder="">


                                        </div>


                                           <div class="col-lg-4 mb-3">

                                            <label class="control-label">Mobile<span class="text-danger">*</span></label>
                                                
                                            <input type="text" minlength="10" maxlength="10" name="eu_mobile" id="example-text-phone-input" value="" class="form-control mob-validate" placeholder="" required onkeypress="return isNumberKey(event,this.id);" onblur="return mobValidate(this.value,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);">

                                            </div>



                                            </div>



                                        <div class="row">
                                            <div class="col-lg-4 mb-3">

                                            <label class="control-label">Landline Number<span class="text-danger">*</span></label>
                                                
                                            <input type="text" minlength="11" maxlength="12" name="eu_landline" autocomplete="of" value="" class="form-control" required id="example-text-input2" onkeypress="return isNumberKey(event,this.id);">

                                            </div>

                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Designation<span class="text-danger">*</span></label>
                                                    
                                            <input type="text" name="eu_designation" value="" pattern="[a-zA-Z0-9'-'\s]*" class="form-control" placeholder="" required  />

                                            <!--/span-->
                                        </div>


                                    </div>

                                        <div class="row">
                                        <div class="col-lg-4 mt-3">

                                            <button type="button" name="add" id="add" class="btn btn-primary  mt-2">Add</button>

                                        </div>
                                        <!--/span-->
                                    </div>

                                    <div id="dynamic_field">
                                    </div>

                                    <h5 class="card-subtitle">Lead Information</h5>

                                      <div class="row">

                                      <div class="col-md-4">
                                                    <label class="control-label">Assigned to Partner<span class="text-danger"></span></label>
                                            
                                                    <?php  
                                                    $res=db_query("select * from partners where status='Active'"); 
                                             
                                                    ?>
                                                       <select name="partner" id="partner" class="form-control">
                                                     <option value="" >---Select---</option>
                                                     <?php while($row=db_fetch_array($res))
                                                     { ?>
                                                 <option <?=(($_GET['partner']==$row['id'])?'selected':'')?>  value='<?=$row['id']?>'><?=$row['name']?></option>
                                                     <?php } ?>
                                                     </select>
                                            </div>
                                          <div class="col-md-4">
                                                    <label class="control-label">Align To<span class="text-danger"></span></label>
                                                       <select name="allign_to" id="users" class="form-control ">
                                                     <option value="" >---Select---</option>
                                                                                                         
                                                     </select>
                                                </div>

                                        <div class="col-md-4">
                                            <label for="example-color-input" class="control-label text-left">No of Student<span class="text-danger">*</span></label><br>

                                            <input  value="" name="quantity" min="0" type="number" pattern="[0-9]*" class="form-control form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>
                                    </div>

                                    <div class="row">

                                          <div class="col-lg-4 mb-3">
                                         
                                         <label class="control-label">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>
                                      
                                             <input type="file" name="user_attachment" class="btn btn-default" /> 
                                      
                                          </div>


                                          <div class="col-lg-3 mb-3">
                                                <label class="control-label ">Expected Close Date<span class="text-danger">*</span></label>
                                                <div class="calendar-field-with-icon">
                                                    <i class="fa fa-fw fa-calendar-week"></i>                                            
                                                    <input type="text" name="expected_close_date" class="form-control" id="datetime" value="" />
                                                </div>                                                   
                                            </div>

                                           <!-- <?php if($license_type == "Renewal") { 

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

                                        <?php } ?> -->


                                          
                                    </div>

                                </div>

                                <div class="button-items">

                                <button type="submit" data-toggle="modal" data-target="#myModal" id="form_data" class="btn btn-primary mt-2" style="margin-bottom:20px">Submit</button>
                                <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2" style="margin-bottom:20px">Cancel</button>

                                </div>
                            <!-- </form> -->
                        </div>
                   

                   </div>
                </div> <!-- end col -->


            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity Call
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
    $row_data = db_fetch_array($query); 
    ?>
    <div class="modal-body">
      <!-- <form action="#" method="post" class="form p-t-20" > -->
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Call Subject<span class="text-danger">*</span></label>
              <select required id="call_type" name="call_type" class="form-control">
                <option value="">---Select---</option>
                <?php
              if ($row_data['role'] == 'TC' || $row_data['user_type'] == 'TC USR') {
               
                  $call_query = db_query("select * from call_subject where subject not like '%visit%' order by id asc");
              }else{
                $call_query = db_query("select * from call_subject where 1 order by id asc"); 
              }

                  while ($call_subject = db_fetch_array($call_query)) {  ?>
                    <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                  <?php  } ?>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Visit/Profiling Remarks<span class="text-danger">*</span></label>
              <textarea required id="remarks" value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" placeholder=""></textarea>
            </div>
          </div>

        </div>

    <div class="modal-footer mb-4">
    <input type="submit" id="save_btn" name="save" value="Save" class="btn btn-primary" />
     
      <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

    </div>
    </form>
 
    <?php include('includes/footer.php') ?>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>

        <script>
        $(document).ready(function(){
     
     
    $('#state').on('change',function(){
        //alert("hi");
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'state_id='+stateID,
                success:function(html){
                    //alert(html);
                    $('#city').html(html);
                }
            }); 
        } 
    }); 
    });
    
    </script>

<script>
        $(document).ready(function() {
            var i = 1;
            var add_btn = $('.add_btn').val();
            $('#add').click(function() {
                i++;
                $('#dynamic_field').append('<div id="row' + add_btn + '"><div class="form-group row d-flex align-items-end"><div class="col-lg-2 mb-3"><label class="control-label">Full Name</label><input name="e_name[]" value="" type="text" value="" class="form-control" placeholder=""></div><div class="col-lg-2 mb-3"><label class="control-label">Email</label><input value="" name="e_email[]" type="email" class="form-control" placeholder=""></div><div class="col-lg-2 mb-3"><label class="control-label">Mobile</label><input type="text" minlength="10" maxlength="10" name="e_mobile[]" value="" class="form-control mob-validate" id="mobile-append'+i+'" onkeypress="return isNumberKey(event,this.id);" onblur="return mobValidate(this.value,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);"></div><div class="col-lg-2 mb-3"><label class="control-label">Designation</label><input type="text" value="" name="e_designation[]" class="form-control" /></div><div class="col-sm-1 mb-3"><span data-repeater-delete="" name="remove" id="' + add_btn + '" class="btn btn-danger btn-sm btn_remove"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>');
                add_btn++;
            });
            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
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

//    function checkValue(res){
//        if(res == 'Yes'){
//            $('#visit_remarks_field').css('display','block');
//            $('#visit_remarks').prop('required',true);
//        }else{
//            $('#visit_remarks_field').css('display','none');
//            $('#visit_remarks').prop('required',false);
//        }
//    }   



        // $(document).ready(function() {
        //     $("#range_qty").ionRangeSlider({
        //         skin: "flat",
        //         //type: "double",
        //         min: 1,
        //         //max: 100,
        //         // from:0
        //     })
        // });

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
                // startDate: '-7d',
                // endDate: '0d',
                autoclose: !0
            });
        }); 

    //   $(function() {
    //         $('#license_end_date').datepicker({
    //             format: 'yyyy-mm-dd',
    //             autoclose: !0
    //         });
    //     }); 

    $('#school_board').on('change',function(){
        
        var board = $(this).val();
        if(board=='Others')
        {
            $("#other_board").prop('required',true);
            $("#other_board_div").css('display','block'); 
        }
        else
        {
            $("#other_board").prop('required',false);
            $("#other_board_div").css('display','none'); 
            $("#other_board").val(''); 
        }
       
    });     
    
    $("#is_group").change(function() {
        if(this.checked) {
            $("#group_name").prop('required',true);
            $("#group_name_div").css('display','block'); 
        }
        else
        {
            $("#group_name").prop('required',false);
            $("#group_name_div").css('display','none');
        
        }
    });

    $(document).ready(function(){
     $('#partner').on('change',function(){
         //alert("hi");
         var partnerID = $(this).val();
         if(partnerID){
             $.ajax({
                 type:'POST',
                 url:'ajaxusers.php',
                 data:'partner_id='+partnerID,
                 success:function(html){
                     //alert(html);
                     $('#users').html(html);
                 }
             }); 
         } 
     });    
     });

</script>

<script>
        $('#example-text-input1').on("keyup",function (evt) {
           if (this.value.length == 1 && this.value !=0)
           {
              swal("please enter 0 first");
              $('#example-text-input1').val('');

           }
        });

        $('#example-text-input1').on("blur",function (evt) {
           if (this.value.length < 11 && this.value.length > 1)
           {
              swal("Landline no should be minimum 11 digits");
              $('#example-text-input1').trigger('focus');
           }
        });

        $('#example-text-input2').on("keyup",function (evt) {
           if (this.value.length == 1 && this.value !=0)
           {
              swal("please enter 0 first");
              $('#example-text-input2').val('');

           }
        });

        $('#example-text-input2').on("blur",function (evt) {
           if (this.value.length < 11 && this.value.length > 1)
           {
              swal("Landline no should be minimum 11 digits");
              $('#example-text-input2').trigger('focus');
           }
        });

        $('.mob-validate').on("blur",function (evt) {
           if (this.value.length < 10 && this.value.length > 1)
           {
              swal("Please enter valid mobile no");
              $('.mob-validate').trigger('focus');
           }
        });

        function mobValidate(value,id)
        {

            if (value.length < 10 && value.length > 0 && value[0] != 0)
               {
                  swal("Please enter valid mobile no");
                  $('.mob-validate').trigger('focus');
               }else if(value[0] == 0){
                  swal("0 not allowed as first digit in mobile no");
                  $('#'+id).trigger('focus');
               }
        }
        
       function mobZeroValidation(value,id)
       {
        //   alert(id);
           if(value[0] == 0){
                  swal("0 not allowed as first digit in mobile no");
                  $('#'+id).val('');
                  $('#'+id).trigger('focus');
               }
       }
       

        function isNumberKey(evt,id)
         {
            try{
                var charCode = (evt.which) ? evt.which : event.keyCode;
          
                if(charCode==46){
                    var txt=document.getElementById(id).value;
                    if(!(txt.indexOf(".") > -1)){
            
                        return false;
                    }
                }
                if (charCode > 31 && (charCode < 48 || charCode > 57) )
                    return false;

                return true;
            }catch(w){
                //alert(w);
            }
         }

         $('#user_attachment').on('change', function() {
            if(this.files[0].size / 1024 / 1024 > 4)
            {
                swal('Please Upload File Less Than 4MB!!')
                $('#user_attachment').val('');
                return false;
            }
            });

            $('#emailer_attachment').on('change', function() {
            if(this.files[0].size / 1024 / 1024 > 4)
            {
                swal('Please Upload File Less Than 4MB!!')
                $('#emailer_attachment').val('');
                return false;
            }
            });

            $(document).ready(function() {
            $('#lead_source').on('change', function() {
                //alert("hi");
                var leadsource = $(this).val();
                if (leadsource) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxsubLeadSource.php',
                        data: 'lead_source=' + leadsource,
                        success: function(html) {
                            //alert(html);
                            $('#sub_lead_source').html(html);
                        }
                    });
                }
            });
        });

        $('#productIss').on('change', function() {

        var productID = $(this).val();
        //   alert(productID);
        if (productID) {
            $.ajax({
                type: 'POST',
                url: 'ajaxProduct.php',
                data: 'productIss=' + productID,
                success: function(response) {
                    $('.modal-footer').show();
                    $('#productTypeIss').html(response);
                    // $('.selectpicker').selectpicker({
                    //     //style: 'btn-primary',
                    //     //size: 2
                    // });

                },
                error: function() {
                    $('#productTypeIss').html('There was an error!');
                }
            });
        } else {
            $('#productTypeIss').html('<option value="" style="color:red">Select product first</option>');
        }
        });
    </script>