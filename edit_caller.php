<?php include('includes/header.php');

if ($_REQUEST['eid']) {

    //"select o.*,tp.*,p.product_name,tpp.product_type from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where o.id=" . $_REQUEST['eid'];


    if ($_SESSION['user_type'] == 'USR' && $_SESSION['user_type'] = 'MNGR') {
        $query = selectEditDetails($_REQUEST['eid'],$_SESSION['team_id']);
        //$query .= " and o.team_id=" . $_SESSION['team_id'];
    }else{
        $query = selectEditDetails($_REQUEST['eid'],' ');
    }
    //$sql = db_query($query);
    $row = db_fetch_array($query);
    @extract($row);

}


if (isset($_POST['edit_caller'])) {

    // echo "<br>";
    // echo "<br>";
    // echo "<br>";
    // echo "<br>";
    // print_r($_POST);die;
    if ($_FILES["user_attachment"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["user_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("orders.php", true);
        } else {
            move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
        }
    }

    $sql = db_query("select * from orders where id=" . $_REQUEST['eid'] . " limit 1");
    $previous_data = db_fetch_array($sql);

    //print_r($previous_data);

    if ($previous_data['source'] != $_POST['source']) {
        $modify_name = $_POST['source'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Lead Source','" . $previous_data['source'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['sub_lead_source'] != $_POST['sub_lead_source']) {
        $modify_name = $_POST['sub_lead_source'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Sub Lead Source','" . $previous_data['source'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['address'] != $_POST['address']) {
        $modify_name = $_POST['address'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Address','" . $previous_data['address'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_email'] != $_POST['eu_email']) {
        $modify_name = $_POST['eu_email'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Email','" . $previous_data['eu_email'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_designation'] != $_POST['eu_designation']) {
        $modify_name = $_POST['eu_designation'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Designation','" . $previous_data['eu_designation'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['eu_mobile'] != $_POST['eu_mobile']) {
        $modify_name = $_POST['eu_mobile'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Mobile','" . $previous_data['eu_mobile'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['school_name'] != $_POST['school_name']) {
        $modify_name = $_POST['school_name'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Organization Name','" . $previous_data['school_name'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['school_board'] != $_POST['school_board']) {
        $modify_name = $_POST['school_board'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','School Board','" . $previous_data['school_board'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['school_email'] != $_POST['school_email']) {
        $modify_name = $_POST['school_email'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','School Email','" . $previous_data['school_email'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['is_group'] != $_POST['is_group']) {
        $modify_name = $_POST['is_group'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Is Group','" . $previous_data['is_group'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['group_name'] != $_POST['group_name']) {
        $modify_name = $_POST['group_name'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Group Name','" . $previous_data['group_name'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['pincode'] != $_POST['pincode']) {
        $modify_name = $_POST['pincode'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Pincode','" . $previous_data['pincode'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['eu_name'] != $_POST['eu_name']) {
        $modify_name = $_POST['eu_name'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Contact Person','" . $previous_data['eu_name'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_landline'] != $_POST['eu_landline']) {
        $modify_name = $_POST['eu_landline'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Contact Landline','" . $previous_data['eu_landline'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['quantity'] != $_POST['quantity']) {
        $modify_name = $_POST['quantity'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Quantity','" . $previous_data['quantity'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['contact'] != $_POST['contact']) {
        $modify_name = $_POST['contact'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Board line Number','" . $previous_data['contact'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['state'] != $_POST['state']) {
        $modify_name = getSingleresult("select name from states where id=".$_POST['state']);
        
        $previous_state = getSingleresult("select name from states where id=".$previous_data['state']);
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','state','" . $previous_state . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['expected_close_date'] != $_POST['expected_close_date']) {
        $modify_name = $_POST['expected_close_date'];
        
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Expected Close Date','" . $previous_data['expected_close_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($_POST['city'] && ($previous_data['city'] != $_POST['city'])) {
        $modify_name = getSingleresult("select city from cities where id=".$_POST['city']);       

        if(!empty($previous_data['city'])){
        $previous_city = getSingleresult("select city from cities where id=".$previous_data['city']);
        }
        //$modify_name = $_POST['city'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','City','" . $previous_city . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($_POST['partner'] && ($previous_data['team_id'] != $_POST['partner'])) {
        $modify_name = getSingleresult("select name from partners where id=".$_POST['partner']);       

        if(!empty($previous_data['team_id'])){
        $previous_city = getSingleresult("select name from partners where id=".$previous_data['team_id']);
        }
        //$modify_name = $_POST['city'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Partner','" . $previous_city . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($_POST['allign_to'] && ($previous_data['allign_to'] != $_POST['allign_to'])) {
        $modify_name = getSingleresult("select name from users where id=".$_POST['allign_to']);       

        if(!empty($previous_data['allign_to'])){
        $previous_city = getSingleresult("select name from users where id=".$previous_data['allign_to']);
        }
        //$modify_name = $_POST['city'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Allign To','" . $previous_city . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }


    $partner = getSingleresult("select name from partners where id='" . $_POST['partner'] . "'");

    if ($row['status'] == 'Undervalidation') {

        $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $row['user_attachement'];

        $_POST['allign_to'] = $_POST['allign_to'] ? $_POST['allign_to'] : '';

        $res=db_query("update  `orders` set `source`='".$_POST['source']."',`sub_lead_source`='".$_POST['sub_lead_source']."',`school_board`='".$_POST['school_board']."',`is_group`='".$_POST['is_group']."',`group_name`='".$_POST['group_name']."',`school_name`='".$_POST['school_name']."',`address`='".$_POST['address']."', `pincode`='".$_POST['pincode']."', `state`='".$_POST['state']."', `city`='".$_POST['city']."',`country`='India', `eu_name`='".$_POST['eu_name']."', `eu_email`='".$_POST['eu_email']."', `eu_landline`='".$_POST['eu_landline']."', `eu_mobile`='".$_POST['eu_mobile']."', `eu_designation`='".$_POST['eu_designation']."', `quantity`='".$_POST['quantity']."',user_attachement='".$target_file."',expected_close_date='".$_POST['expected_close_date']."',team_id='".$_POST['partner']."',allign_to='".$_POST['allign_to']."',status='Pending',created_date=now() where id=".$_POST['eid']);

    } else {

        $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $row['user_attachement'];

        $_POST['allign_to'] = $_POST['allign_to'] ? $_POST['allign_to'] : $row['allign_to'];

        $res=db_query("update  `orders` set `source`='".$_POST['source']."',`sub_lead_source`='".$_POST['sub_lead_source']."',`school_board`='".$_POST['school_board']."',`is_group`='".$_POST['is_group']."',`group_name`='".$_POST['group_name']."',`school_name`='".$_POST['school_name']."',`address`='".$_POST['address']."', `pincode`='".$_POST['pincode']."', `state`='".$_POST['state']."', `city`='".$_POST['city']."',`country`='India', `eu_name`='".$_POST['eu_name']."', `eu_email`='".$_POST['eu_email']."', `eu_landline`='".$_POST['eu_landline']."', `eu_mobile`='".$_POST['eu_mobile']."', `eu_designation`='".$_POST['eu_designation']."', `quantity`='".$_POST['quantity']."',user_attachement='".$target_file."',expected_close_date='".$_POST['expected_close_date']."' where id=".$_POST['eid']);
    }




    if ($res && $_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'RCLR' && $_SESSION['user_type'] != 'OPERATIONS') {

        redir("orders.php?update=success", true);
    } else if ($_SESSION['user_type'] == 'CLR') {
        redir("orders_caller.php?update=success", true);
    } else if ($_SESSION['user_type'] == 'OPERATIONS') {
        redir("manage_orders.php?update=success", true);
    } else if($_SESSION['user_type'] == 'RCLR') {
        redir("renewal_caller.php?update=success", true);
     }else {
        redir("view_order.php?id=" . $_POST['eid'], true);
    }
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

                                    <small class="text-muted">Home > Edit Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Lead</h4>
                                </div>
                            </div>
                            <!-- <h5 class="card-subtitle">Edit Lead &nbsp;&nbsp;<?= $r_name ?></h5> -->
                            <div class="clearfix"></div>
                        <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">
                        <div data-simplebar class="add_lead">
                                
                                <!--/row-->
                                <!-- extra filels for renewal edit -->
                                <h5 class="card-subtitle">Partner Information</h5>
                                <div class="row">
                                 <div class="col-lg-4 mb-3">

                                        <label class="control-label">Organization Name<span class="text-danger">*</span></label>

                                        <input readonly name="r_name" type="text" value="<?= $r_name ?>" class="form-control" placeholder="" required="" />


                                    </div>
                                    <!--/span-->
                                    <div class="col-lg-4 mb-3">

                                        <label class="control-label">Email<span class="text-danger">*</span></label>

                                        <input readonly value="<?= $r_email ?>" name="r_email" type="email"  class="form-control" required />


                                    </div>
                                    <div class="col-lg-4 mb-3">

                                        <label class="control-label">Submitted By<span class="text-danger">*</span></label>

                                        <input readonly type="text" min="0" name="r_user" value="<?= $r_user ?>" class="form-control" required />

                                    </div>

                                    

                                    <!--/span-->
                                </div>
                                  <!-- extra filels End  -->

                                <h5 class="card-subtitle">Customer Information</h5>

                                <div class="row">
                                    <div class="col-lg-3 mb-3">

                                        <label class="example-text-input">Assigned to Partner<span class="text-danger">*</span></label>
                                        <?php
                                        $res = db_query("select * from partners where id <> 45"); ?>
                                        <select name="partner" id="partner" class="form-control" required>
                                            <option value="">---Select---</option>
                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                <option <?= (($team_id == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>

                                    <div class="col-lg-3 mb-3">
                                    <?php $res = db_query("select * from users where team_id=" . $team_id);
                                    if ($allign_to) { ?>
                                    <label class="control-label">Align To<span class="text-danger">*</span><span class="text-danger"></span></label>

                                    <select name="allign_to" id="users" class="form-control " required data-validation-required-message="This field is required">
                                        <option value="">Select User</option>
                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                <option <?= (($allign_to == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                            <?php } ?>
                                    </select>
                                   <?php }else{ ?>
                                    <label class="control-label">Align To<span class="text-danger">*</span><span class="text-danger"></span></label>
                                        <select name="allign_to" id="users" class="form-control " required data-validation-required-message="This field is required">
                                            <option value="">Select User</option>
                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($allign_to == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                        </select>
                                    <?php  } ?>
                                    </div>

                                    <div class="col-lg-3 mb-3">

                                        <label for="example-text-input" class="">Lead Source<span class="text-danger">*</span></label>
                                            <select name="source" class="form-control" id="lead_source" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from lead_source where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                <?php } ?>

                                            </select>
                                            
                                        </div>
                                    <div class="col-lg-3 mb-3" id="sub_lead_source">
                                            <?php if ($sub_lead_source) {
                                                $query = db_query("SELECT * FROM sub_lead_source WHERE lead_source = '" . $source . "'  ORDER BY sub_lead_source ASC");
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

                                    <div class="row">
                                    <div class="col-lg-3 mb-3">

                                    <label class="control-label ">School Board<span class="text-danger">*</span></label>
                                            
                                        <select name="school_board" id="school_board" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                            <option value="">---Select---</option>
                                            <option value="CBSE" <?=(($school_board=='CBSE')?'selected':'')?>>CBSE</option>
                                            <option value="ICSE" <?=(($school_board=='ICSE')?'selected':'')?>>ICSE</option>
                                            <option value="IB" <?=(($school_board=='IB')?'selected':'')?>>IB</option>
                                            <option value="IGCSE" <?=(($school_board=='IGCSE')?'selected':'')?>>IGCSE</option>
                                            <option value="STATE" <?=(($school_board=='STATE')?'selected':'')?>>STATE</option>
                                            <option value="Others" <?=(($school_board !='CBSE' && $school_board !='ICSE' && $school_board !='IB' && $school_board !='IGCSE' && $school_board !='STATE')?'selected':'')?>>Others</option>
                                        </select>
                                         

                                    </div>
                                        
                                    <div class="col-lg-3 mb-3">
                                                    
                                        <label class="control-label">Organization Name<span class="text-danger">*</span></label>
                                        
                                        <input type="text" name="school_name" value="<?= $school_name ?>" class="form-control" placeholder="" required>
                                                    
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                              
                                        <label class="control-label ">Is Group</label>
                                        
                                        <input type="checkbox" name="is_group" id="is_group" value="yes" class="form-control" placeholder="" <?=($is_group == "yes")?"checked":""?> style="width: 18px;">
                                                    
                                     </div>

                                    <div class="col-lg-3 mb-3" id="group_name_div" style="display: none">
                                              
                                        <label class="control-label ">Group Name<span class="text-danger">*</span></label>
                                        
                                        <input type="text" name="group_name" id="group_name" value="<?= $group_name ?>" class="form-control" placeholder="">
                                                    
                                    </div>

                                    <!-- <div class="col-lg-3 mb-3">
                                                    
                                        <label class="control-label ">Board-line<span class="text-danger">*</span></label>
                                        
                                        <input type="number" min="0" name="contact" value="<?= $contact ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" >
                                                    
                                    </div> -->
                                            <!--/span-->

                                    <!-- <div class="col-lg-3 mb-3">
                                                
                                        <label class="control-label ">Email Id<span class="text-danger">*</span></label>
                                        
                                        <input type="email" min="0" name="school_email" value="<?= $school_email ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" >
                                                    
                                    </div> -->

                                  

                                    <div class="col-lg-3 mb-3">

                                        <label class="example-text-input">States<span class="text-danger">*</span></label>

                                        <?php $res = db_query("select * from states"); 

                                        //print_r($res); die;

                                        ?>
                                        <select name="state" id="state" class="form-control" required data-validation-required-message="This field is required">
                                        <option value="">---Select---</option>
                                        <?php while ($row = db_fetch_array($res)) { ?>
                                            <option <?= (($row['id'] == $state) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                        <?php } ?>
                                        </select>

                                        </div>
                                        <div class="col-lg-3 mb-3" >

                                        <?php if ($city) {
                                        $query = db_query("SELECT * FROM cities WHERE state_id = ".$state."  ORDER BY city ASC");

                                        $city = getSingleresult("select city from orders where id='" . $_REQUEST['eid'] . "'");
                                        $rowCount = mysqli_num_rows($query);

                                        if ($rowCount > 0) { ?>
                                            
                                                <label class="control-label">City<span class="text-danger">*</span></label>
                                                <select name="city" id="city" class="form-control" >
                                                <option value="">Select City</option>
                                                <?php 
                                            while ($row = db_fetch_array($query)) { ?>
                                            <option value="<?=$row['id']?>" <?php if($row['id'] == $city){echo "selected";} ?>> <?=$row['city']?></option> 

                                        <?php  } ?>
                                            </select>
                                        <?php }
                                        } ?>
                                        </div>


                                        <div class="col-lg-3 mb-3">

                                        <label class="control-label">Pin Code<span class="text-danger">*</span></label>

                                        <input type="number" min="0" name="pincode" value="<?= $pincode ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                        </div>



                                        <div class="col-lg-3 mb-3">

                                        <label class="control-label">Address<span class="text-danger">*</span></label>

                                        <textarea name="address" value="" rows="5" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?= $address ?></textarea>

                                        </div>

                                        </div>



                                <!--/row-->
                                <h5 class="card-subtitle">Decision Maker Information</h5>
                                <div class="row">
                                    <div class="col-lg-3 mb-3">

                                        <label class="example-text-input">Full Name<span class="text-danger">*</span></label>

                                        <input name="eu_name" type="text" value="<?= $eu_name ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                    </div>
                                    <!--/span-->
                                    <div class="col-lg-3 mb-3">

                                        <label class="example-text-input">Email<span class="text-danger">*</span></label>

                                        <input value="<?= $eu_email ?>" name="eu_email" type="email" class="form-control" required />

                                    </div>

                                    <div class="col-lg-3 mb-3">

                                        <label class="example-text-input">Mobile<span class="text-danger">*</span></label>

                                        <input type="number" min="0" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control"  required />

                                    </div>
                                    <!--/span-->
                                    <div class="col-lg-3 mb-3">

                                        <label class="example-text-input">Designation<span class="text-danger">*</span></label>

                                        <input type="text" name="eu_designation" value="<?= $eu_designation ?>" class="form-control" required />

                                    </div>

                                    <div class="col-lg-3 mb-3">
                                    <label for="example-search-input" >Landline Number<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="eu_landline" autocomplete="of" value="<?= $eu_landline ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                    </div>
                                </div>

                                <h5 class="card-subtitle">Lead Information</h5>
                                <div class="row">
                                                                                   
                                     <div class="col-lg-4 mb-3">

                                        <label for="example-color-input" class="control-label text-left">Quantity<span class="text-danger">*</span></label><br>


                                        <input type="text" name="quantity" id="range_qty" value="<?= ($quantity ? $quantity : 1) ?>">

                                    </div>

                                    <div class="col-lg-4 mb-3">

                                        <label class="example-search-input">Attachment (Max: 4MB)<span class="text-danger"></span></label>

                                        <input type="file" name="user_attachment" class="form-control" />

                                        <?php if($user_attachement){ ?>
                                            <a href="<?= $user_attachement ?>">View/Download</a>
                                            <!-- <img src="<?= $user_attachement ?>" style="width:50px; height:50px" /> -->
                                        <?php } ?>
                                       
                                        <input type="hidden" name="old_user_attachment" value="<?= $user_attachement ?>" class="form-control">
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                                <label class="control-label ">Expected Close Date<span class="text-danger">*</span></label>
                                                <div class="calendar-field-with-icon">
                                                    <i class="fa fa-fw fa-calendar-week"></i>                                            
                                                    <input type="text" name="expected_close_date" class="form-control" id="datetime" value="<?= $expected_close_date ?>" />
                                                </div>                                                   
                                            </div>
                                </div>
                               
                                <div class="button-items">
                                    <input type="hidden" name="eid" value=<?= $_REQUEST['eid'] ?> />
                                    <button type="submit" name="edit_caller" class="btn btn-primary  mt-2">Submit</button>
                                    <button type="button" onclick="history.go(-1);" class="btn btn-danger mt-2">Cancel</button>

                              </div>
                        </form>
                        </div>
                    </div>
                </div> <!-- end col -->


            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?php include('includes/footer.php') ?>

    <script>
        $(document).ready(function() {
            $('#state').on('change', function() {
                var stateID = $(this).val();
                if (stateID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxcity.php',
                        data: 'state_id=' + stateID,
                        success: function(html) {
                            $('#city').html(html);
                        }
                    });
                } else {
                    $('#city').html('<option value="">Select state first</option>');
                }
            });

            $('#partner').on('change', function() {
                 //alert("hi");
                var partnerID = $(this).val();
                if (partnerID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxusers.php',
                        data: 'partner_id=' + partnerID,
                        success: function(html) {
                            //alert(html);
                            $('#users').html(html);
                        }
                    });
                }
            });

        });
        $(document).ready(function() {
            $('#check').click(function() {
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


<script>
    $("#range_quantity").ionRangeSlider({
                skin: "flat",
               // type: "double",
                min: 15,
                max: 300,
               // from: 0,
                //to: 15,
                step: 15
            })

            $("#range_qty").ionRangeSlider({
                skin: "flat",
              
                min: 1,
               
            })

            $(document).ready(function() {
      if($("#is_group").is(':checked')) {
        $("#group_name").prop('required',true);
        $("#group_name_div").css('display','block');
      }

        var wfheight = $(window).height();
        $('.add_lead').height(wfheight - 260);
        });

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
            $("#group_name").val('');
            $("#group_name_div").css('display','none');
        
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

        $(function() {
            $('#datetime').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-7d',
                // endDate: '0d',
                autoclose: !0
            });
        }); 
    </script>
   