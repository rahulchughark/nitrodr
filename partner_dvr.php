<?php include('includes/header.php');
include_once('helpers/DataController.php');

$data_oops = new DataController();

if ($_REQUEST['id']) {
    $sql = DVRselect_query($_REQUEST['id']);

    $data = db_fetch_array($sql);
   
    @extract($data);
} else {
    redir("manage_orders.php", true);
}

if (isset($_POST['lead_type']) && $_POST['lead_type'] != '') {

    $_POST['lead_type'] = 'BD';

    if ($_FILES["user_attachment"]) {

        $target_dir = "uploads/";
        $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["user_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("dvr.php", true);
        } else {
            move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
        }
    }
        $log = [
            'dvr_flag'          => 0,
            'lead_type'         => $_POST['lead_type'],
            'created_date'      => $created_date,
            'convert_date'      => date('Y-m-d H:i:s'),
            // 'user_attachement'  => $target_file
        ];
        $where = [
            'id'=>$_GET['id'],
        ];
        $sql = $data_oops->update($log, "orders",$where);

        redir("dvr.php?update=success&cnt=" . $cont_final, true);
   
}

if ($_POST['remarks'] && !$_POST['activity_edit']) {
    $log = [
        'pid'          => $_POST['pid'],
        'description'  => htmlspecialchars($_POST['remarks'], ENT_QUOTES),
        'activity_type'=> 'DVR',
        'call_subject' => $_POST['call_subject'],
        'added_by'     => $_SESSION['user_id']
    ];
    $res = $data_oops->insert($log, "activity_log");

   //  $email = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller from orders where id=" . intval($_POST['pid']));
   //  $data = db_fetch_array($email);

   //  $sm_email = getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='" . $data['team_id'] . "'");
   //  if ($sm_email)
   //      $mail->AddCC($sm_email);

   // // $mail->AddCC("prashant.dongrikar@arkinfo.in", "Prashant");
   //  $mail->AddCC("pradnya.chaukekar@arkinfo.in");
   //  $mail->AddCC("kailash.bhurke@arkinfo.in");
   //  $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);
   //  $mail->AddCC($_SESSION['email']);

   //  if ($data['lead_type'] == 'LC') {
   //      if ($data['caller'] != '') {
   //          $caller_email1 = db_query("select users.email as call_email from users join callers on users.id=callers.user_id where callers.id=" . $data['caller']);
   //          $caller_email = db_fetch_array($caller_email1);
   //          $mail->AddAddress($caller_email['call_email']);
   //      }
        
   //      $mail->AddCC($manager_email);
   //      $mail->AddCC("virendra@corelindia.co.in");

   //  } else {

   //      $mail->AddAddress($manager_email);
   //      $mail->AddCC($data['r_email']);
        
   //  }
   //      $mail->Subject = $data['company_name'] . " - New Log a Call";
   //      $mail->Body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on SketchUp DR Portal with details as below:-<br><br>
   //      <ul>
   //      <li><b>Partner Name</b> : " . $data['r_name'] . " </li>
   //      <li><b>Account Name</b> : " . $data['company_name'] . " </li>
   //      <li><b>Contact Number</b> : ". $data['eu_mobile'] ." </li>
   //      <li><b>Email ID</b> : ". $data['eu_email'] ." </li>
   //      <li><b>Lead Type</b> : " . $data['lead_type'] . " </li>
   //      <li><b>Call Subject</b> : " . htmlspecialchars($_POST['call_subject'], ENT_QUOTES) . " </li>
   //      <li><b>Description</b> : " . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . " </li>
   //      <li><b>Quantity</b> : " . $data['quantity'] . " </li>
   //      <li><b>Projected Close Date</b> : " . $data['partner_close_date'] . " </li></ul><br>
   //      Thanks,<br>
   //      SketchUp DR Portal
   //      ";
    
   //   $mail->Send();
   //  $mail->ClearAllRecipients();
}

if($_POST['association_name']){
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Association Name','".$association_name."','".$_POST['association_name']."',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update orders set association_name='".$_POST['association_name']."' where id=".$_GET['id']);
}


?>

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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > View Daily Visit</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Daily Visit</h4>
                                </div>
                            </div>
							<div class="clearfix"></div>
                            <div data-simplebar class="add_lead">
							<div class="accordion" id="accordionExample2">
                               <div class="card mb-0 pt-2 shadow-none">
								  <!-- <div class="card-header" id="headingOne4">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
                                                   Product Info
                                                </button>
                                            </h5> 
                                        </div>-->
										
                            <div id="collapseOne4" class="" aria-labelledby="headingOne4" data-parent="#accordionExample2">        
                                    <div class="row">

                                        <div class="col-lg-12 ">
                                            <table class="table" id="user">
                                                <tbody>
                                                   <!--  <tr>
                                                        <td width="35%">Product Name</td>
                                                        <td width="65%"><?= $product_name ?></td>
                                                        <input type="hidden" name="product_id" id="product_id" value="<?= $product_name ?>" />
                                                        <input type="hidden" name="edit_id" id="edit_id" value="<?= $_REQUEST['id'] ?>" />
                                                    </tr>
                                                    <tr>
                                                        <td>Product Type</td>
                                                        <td>
                                                            <?= $product_type ?>&nbsp;

                                                            <?php if ($product_name == 'CDGS Fresh' && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                                                <button class="btn1 btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                                            <?php } ?>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
					</div>
</div>					
									
									
									<div class="card" style="display:none">
                                       
										
										 <div class="card-header" id="headingOne5">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                   Partner Info
                                                </button>
                                            </h5>
                                        </div>
										<div id="collapseOne5" class="" aria-labelledby="headingOne5" data-parent="#accordionExample2">  	
                                           
                                            <table style="clear: both" class="table table-bordered table-striped" id="user">
                                                <tbody>
                                                    <tr>
                                                        <td width="35%">Partner Name</td>
                                                        <td width="65%"><?= $r_name ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Partner Email</td>
                                                        <td>
                                                            <?= $r_email ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Submited By</td>
                                                        <td>
                                                            <?= $r_user ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        
										
										</div>
                                    </div>
									
									
									<div class="card mb-0 pt-2 shadow-none">
									<div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                   Customer Information
                                                </button>
                                            </h5>
                                        </div>
										
									<div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">  	
                                   
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <table class="table" id="user">
                                                <tbody>
                                                    <tr>
                                                        <td width="35%">Lead Source</td>
                                                        <td width="65%"><?= $source ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lead Type</td>
                                                        <td>
                                                            <?= $lead_type ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Company Name</td>
                                                        <td>
                                                            <?= $company_name ?>
                                                            <input type="hidden" name="company_name" value="<?= $company_name ?>" id="company">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Parent Company</td>
                                                        <td>
                                                            <?= $parent_company ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                    
                                    <form method="post" name="save_association_form">
                                   <?php if($type_id==1 || $type_id==2){
                                   if(!empty($association_name)){ ?>
                                   <td>Association Name</td>
                                    <td id="edit_association">

                                        <?= $association_name ?>
                                        <input type="hidden" name="edit_association_name" value="<?= $association_name ?>" id="edit_association_name">
                                        <?php if ($created_by == $_SESSION['user_id'] ) { ?>
                                        <button class="btn1 btn-primary" onclick="change_association('<?= $association_name ?>')">Edit</button>
                                        <?php } ?>
                                    </td>
                                        <?php }else{
                                            if($created_by == $_SESSION['user_id']){?>
                                            <td>Association Name</td>
                                            <td><input type="text" name="association_name" value="<?= $association_name ?>">
                                            <button type="submit" class="btn1 btn-primary">Save</button>
                                        </td>
                                        <?php } } }?>
                                    </form>
                                </tr>

                                                    <tr>
                                                        <td>Landline Number</td>
                                                        <td>
                                                            <?= $landline ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Industry</td>
                                                        <td>
                                                            <?= getSingleresult("select name from industry where id=" . $industry) ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($sub_industry) { ?><tr>
                                                            <td>Sub Industry</td>
                                                            <td>
                                                                <?= getSingleresult("select name from sub_industry where id=" . $sub_industry) ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td>Region</td>
                                                        <td>
                                                            <?= $region ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address</td>
                                                        <td>
                                                            <?= $address ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pin Code</td>
                                                        <td>
                                                            <?= $pincode ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>State</td>
                                                        <td>
                                                            <?= getSingleresult("select name from states where id=" . $state) ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>City</td>
                                                        <td>
                                                            <?= $city ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Country</td>
                                                        <td>
                                                            <?= $country ?>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
         
                            </div>
                            </div>		
 
                    <div class="card mb-0 pt-2 shadow-none">							  
                                <div class="card-header" id="headingOne7">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                   Decision Maker/Proprietor/Director/End User Details
                                                </button>
                                            </h5>
                                        </div>
								
                                <div id="collapseOne7" class="" aria-labelledby="headingOne7" data-parent="#accordionExample2">      
									
									<div class="row">

                                        <div class="col-lg-12">
                                            <table class="table" id="user">
                                                <tbody>
                                                    <tr>
                                                        <td width="35%">Full Name</td>
                                                        <td width="65%"> <?= $eu_name ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email</td>
                                                        <td>
                                                            <?= $eu_email ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Landline Number</td>
                                                        <td>
                                                            <?= $eu_landline ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Department</td>
                                                        <td>
                                                            <?= $department ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mobile</td>
                                                        <td>
                                                            <?= $eu_mobile ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Designation</td>
                                                        <td>
                                                            <?= $eu_designation ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Role</td>
                                                        <td>
                                                            <?= $eu_role ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Account Visited</td>
                                                        <td>
                                                            <?= $account_visited ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Visit/Profiling Remarks</td>
                                                        <td>

                                            <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>: <?= $visit_remarks ?>

                                            </td>
                                            </tr>
                                            <tr>
                                                <td>Usage Confirmation Received from</td>
                                                <td>
                                                    <?= $confirmation_from ?>
                                                </td>
                                            </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                   
</div>
</div>

								   <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne8">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                    Activity History
                                                </button>
                                            </h5>
											
											  <?php if ($_SESSION['user_type'] != 'ADMIN' || $_SESSION['user_type'] != 'SUPERADMIN' || $_SESSION['user_type'] != 'SALES MNGR'|| $_SESSION['user_type'] != 'OPERATIONS') { ?>
                                        
                                        <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $company_name?>')" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-2">Log a Call</button></a>
                                        <?php } ?>
                                        </div>

                                        <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2"> 

                                  
                                   
                                    <div class="row">
                                            <div class="col-md-12">
                                            <?php
                                                            $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
                                                            $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                                            $count = mysqli_num_rows($new);
                                                            $i = $count;
                                                            if ($count) {
                                                                echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr></thead>';

                                                                while ($data_n = db_fetch_array($new)) { ?>
<tbody>
                                                    <tr style="text-align:center">
                                                        <td><?= $i ?></td>
                                                        <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                        <td><?= $data_n['description'] ?></td>
                                                        <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                        <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>

                                                    </tr>
                                                                </tbody>
                                            <?php $i--;
                                                                }
                                                                echo "</table>";
                                                            } ?>
                                            </div>
                                    </div>
                                        
										
										</div>
                                    </div>
									
									<div class="card mb-0 pt-2 shadow-none">
									 <div class="card-header" id="headingOne9">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne9" aria-expanded="true" aria-controls="collapseOne9">
                                                  Lead Information
                                                </button>
                                            </h5>
                                        </div>
								
								
                                    <div id="collapseOne9" class="" aria-labelledby="headingOne9" data-parent="#accordionExample2"> 

                                    <div class="row">
                                    <div class="col-lg-12">

                                    <table class="table">
                                    <form name="update_dvr" action="#" method='post' enctype="multipart/form-data">
                                     <tbody>
                                        <tr>
                                        <td width="35%">Type of License</td>
                                        <td width="65%"><?= $license_type ?></td>
                                        </tr>
                                        <tr>
                                        <td>Quantity</td>
                                            <td>
                                            <?= $quantity ?> User(s)
                                            <input type="hidden" name="quantity" value="<?= $quantity ?>" id="qty">
                                            </td>
                                        </tr>


                                        <tr>
                                        <td>Call Type</td>
                                        <td>
                                        <?php if ($data['call_type']) { ?> <?= getSingleresult("select name from call_type where id=" . $data['call_type']) ?> <?php } ?>
                                        </td>
                                        </tr>
                                        <?php if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR') { ?>

                                         <?php if($lead_type == '')
                                         { ?>   

                                        <tr>
                                        <td>Add as Lead</td>
                                        <td>
                                        <input name="submit_dvr" value="1" type="checkbox" required id="md_checkbox_21" class="filled-in check-col-pink">
                                        <label for="md_checkbox_21"></label>
                                        </td>
                                        </tr>

                                        <tr id="ltype" style="display:none">
                                        <td>Lead Type</td>
                                        <td>BD</td>
                                            <input name="lead_type" value="BD" type="hidden" required> 
                                        <!-- <td>
                                        <select class="form-control" id="ltype_dd" name="lead_type" required>
                                        <option value="">--Select--</option>
                                        <option value="LC" <?= (($lead_type == 'LC') ? 'selected' : '') ?>>LC</option>
                                        <option value="BD" <?= (($lead_type == 'BD') ? 'selected' : '') ?>>BD</option>
                                        </select>
                                        </td> -->
                                        </tr>

                                        <?php } else{ ?>
                                         <tr>
                                         <td>Lead Type</td>
                                         <td>
                                         <?=$lead_type;?>
                                        </td>
                                        </tr>
                                        <?php } ?> 
                                    
                                                           
                            <tr id="Attachment" style="display:none">
                            <td>Attachment <br>(Max: 4MB)<span class="text-danger">*</span></td>
                            <td><input type="file" id="attFiles" name="user_attachment" class="form-control" value="" />
                                    </td>
                                        </tr>
                                                    </tbody>
													 <?php } ?>
											</form>
                                          </table>
											
											
                                        </div>
                                    </div>
									
									
									</div>
									</div>
									
									

                                    <div class="button-items1">
                                        <button id="sub_btn" style="display:none" type="submit" name="save" class="btn1 btn-primary  mt-2">Submit as Lead</button>
                                        <input type='hidden' value="<?= $created_date ?>" name="created_date" />

                                        <a href="edit_dvr.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-warning mt-2">Edit</button></a>
                                        <button type="button" onclick="javascript:history.go(-1)" class="btn1 btn-danger mt-2">Back</button>

                                    </div>

                               


         </div>
 </div>                          
</div>
</div>

						   </div>
                        </div> <!-- end col -->


                    </div> <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <div id="myModal" class="modal fade" role="dialog">
            </div>

            <?php include('includes/footer.php');
            $activity_log = db_query("select call_subject from activity_log where pid=".$_REQUEST["id"]."  order by id desc limit 1");
            while($activity_arr = db_fetch_array($activity_log)){
              $log_arr[] = $activity_arr['call_subject'];
            }
             ?>


            <?php if (mysqli_num_rows(db_query("select * from tbl_lead_product where lead_id=" . $_REQUEST['id'])) == 0) { ?>
                <!-- <script>
                    var id = $('#edit_id').val();
                    var company = $('#company').val();
                    var qty = $('#qty').val();
                    $(function() {
                        $.ajax({
                            type: 'POST',
                            url: 'lead_product.php',
                            data: {
                                id: id,
                                company: company,
                                qty: qty
                            },
                            success: function(response) {
                                $("#selfReview").html();
                                $("#selfReview").html(response);
                                $('#selfReview').modal('show');

                            }
                        });
                    });
                </script> -->
            <?php } ?>


            <script id="rendered-js">
        		$(document).ready(function () {
  $('#multiselect').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });

    $('#multiselect1').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });

});

function change_association(a) {
            document.getElementById("edit_association").innerHTML = '<input type="text" value="' + a + '" id="new_association"/> <button onclick="save_association()" class="btn btn-warning">Save</button>'

        }

        function save_association() {
            
            var new_assoc = document.getElementById("new_association").value;
           
            if (new_assoc) {
                swal({
                    title: "Are you sure?",
                    text: "You want to change the association name for this lead!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Change!",
                    cancelButtonText: "No, cancel modification!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "update_association_name.php?id=<?= $_GET['id'] ?>&association=" + new_assoc,
                            success: function(result) {
                                if (result) {
                                    swal({
                                        title: "Done!",
                                        text: "Lead Modified.",
                                        type: "success"
                                    }, function() {
                                        location.reload();

                                    });
                                }
                            }
                        });

                    } else {
                        swal("Cancelled", "Lead unchanged!", "error");
                    }
                });
            }
        }

                function change_product_type(id, type) {
                    //alert(id);
                    //alert(type);
                    swal({
                        title: "Are you sure?",
                        text: "Are you sure you would like to change Product Type ?",
                        type: "warning",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        confirmButtonText: "Yes!",
                        confirmButtonColor: "#ec6c62"
                    }, function() {
                        $.ajax({
                                type: 'POST',
                                url: 'iss_product_change.php',
                                data: {
                                    lead_id: id,
                                    type: type,

                                },
                                success: function(response) {
                                    return false;
                                }
                            }).done(function(data) {
                                swal("Product Type changed successfully!");
                                setTimeout(function() {
                                    location.reload();
                                }, 1000)
                            })
                            .error(function(data) {
                                swal("Oops", "We couldn't connect to the server!", "error");
                            });
                    })
                }

                function add_activity(a,company_name) {
                    $.ajax({
                        type: 'POST',
                        url: 'add_activity.php',
                        data: {
                            pid: a,
                            company_name:company_name
                        },
                        success: function(response) {
                            $("#myModal").html();
                            $("#myModal").html(response);
                            $('#myModal').modal('show');
                            $(".modal-backdrop").addClass("fade");
                        }
                    });
                }
                //# sourceURL=pen.js
            </script>

            <script>
                $(document).ready(function() {
                    $('#md_checkbox_21').click(function() {
                        // var product_lead = $('#product_id').val();
                        if ($(this).is(':checked')) {

                            $("#ltype").show();
                            $("#sub_btn").show();
                            // $("#ltype_dd").prop('required', true);
                        } else {
                            $("#sub_btn").hide();
                            $("#ltype").hide();
                            // $("#ltype_dd").prop('required', false);
                        }
                    });


                    /*24012019*/

                    // $('#ltype_dd').change(function() {
                    //     var lead_type = $('#ltype_dd').val();
                        
                    //     if (lead_type == 'LC') {                           
                    //         $('#Attachment').show();
                    //         $('#validation_type').show();
                            
                    //     } else {
                    //         $('#Attachment').hide();
                    //         $('#validation_type').hide();
                    //         $('#emailer_log_subject').hide(); 
                    //         $('#emailer_log_remark').hide();
                    //         $('#profiling_log_subject').hide(); 
                    //         $('#profiling_log_remark').hide();
                    //     }

                    // });

            //         $('#profiling_type').change(function() {               
            //         var val_type = $('#profiling_type').val();                        
            //         var call_subject = <?php echo json_encode($log_arr); ?>;   

            //             if(val_type=='profiling_validation' && call_subject!='Profiling Call'){
            //                 $('#profiling_log_subject').show(); 
            //                 $('#profiling_log_remark').show();   
            //                 $('#emailer_log_subject').hide(); 
            //                 $('#emailer_log_remark').hide();    
            //                 $('#Attachment').hide();                         
            //             }else if(val_type=='emailer_validation'){
                             
            //                 $('#Attachment').show();
            //                 if(call_subject!='Profiling Call'){               
            //                 $('#emailer_log_subject').show(); 
            //                 $('#emailer_log_remark').show();
            //                 $('#profiling_log_subject').hide(); 
            //                 $('#profiling_log_remark').hide();
            // }   

            //             }
            //         });


                    $('#sub_btn').click(function(event) {
                        // var lead_type = $('#ltype_dd').val();
                        //var attFiles = $('#attFiles').val();
                        // attFiles = document.getElementById('attFiles');

                        // if (lead_type == "LC") {
                        //     if (attFiles.files.length > 0) {
                        //         return true;

                        //     } else {
                        //         event.preventDefault();
                        //         swal({
                        //                 title: "Warning?",
                        //                 text: "Attachment is required to clarify BD efforts, still want to proceed?",
                        //                 type: "warning",
                        //                 showCancelButton: true,
                        //                 confirmButtonClass: "btn-danger",
                        //                 confirmButtonText: "Yes, submit it!",
                        //                 closeOnConfirm: false
                        //             },
                        //             function(isConfirm) {
                        //                 if (isConfirm) {
                        //                     $('form[name="update_dvr"]').submit();
                        //                     return true;
                        //                 } else {
                        //                     return false;
                        //                 }
                        //             });
                        //         // swal("Alert", "Attachment is required to clarify BD efforts, still want to proceed?", "error");  
                        //         // return false; 
                        //     }
                        // } else if (lead_type == 'BD') {
                            //alert(lead_type);
                            $('form[name="update_dvr"]').submit();
                            return true;
                        // }
                    });
                });


                function view_activity(a) {
                    var type = 'DVR';
                    $.ajax({
                        type: 'POST',
                        url: 'view_activity.php',
                        data: {
                            pid: a,
                            type: type
                        },
                        success: function(response) {
                            $("#myModal").html();
                            $("#myModal").html(response);
                            $('#myModal').modal('show');
                        }
                    });
                }

                function confirm_lc(type) {
                    if (type == 'LC') {
                        var fd = new FormData();
                        //  var files = $('#file')[0].files[0];
                        //  fd.append('file',files);

                        // AJAX request
                        $.ajax({
                            url: 'upload.php',
                            type: 'post',
                            data: fd,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response != 0) {
                                    // Show image preview
                                    $('#preview').append("<img src='" + response + "' width='100' height='100' style='display: inline-block;'>");
                                } else {
                                    alert('file not uploaded');
                                }
                            }
                        });
                    }
                }

                function edit_activity(id) {
                    $.ajax({
                        type: 'POST',
                        url: 'edit_activity.php',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            $("#myModal").html();
                            $("#myModal").html(response);
                            $('#myModal').modal('show');
                        }
                    });
                }
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
            <style>
                .dropdown-menu .inner {
                    height: 150px !important;
                }

                .data_export_box .dropdown-menu.show {
                    width: 300px !important;
                    min-width: 300px !important;
                }
            </style>
			 <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 220);
            });
        </script>