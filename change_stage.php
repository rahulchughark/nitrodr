<?php
include('includes/include.php');  
include_once('helpers/DataController.php');

// if (!empty($_FILES['attachments']['name'][0])) {
//     $target_dir = "uploads/";
//     $f = 1;
//     foreach ($_FILES['attachments']['name'] as $key => $value) {
//         $file_extension = pathinfo(basename($_FILES['attachments']['name'][$key]), PATHINFO_EXTENSION);
//         $target_file = $target_dir .$f.'_'. time().".".$file_extension;        
//         if (move_uploaded_file($_FILES['attachments']['tmp_name'][$key], $target_file)) {
//             $uploaded_files[] = $target_file;
//         } else {
//             echo 'error';
//             exit;
//         }
//         $f++;
//     }
//     $file_names = implode(',', $uploaded_files);
// }else{
//     $file_names = getSingleresult("SELECT po_attachment from orders where id=".$_REQUEST['lead_id']);
// }

$update_payment = new DataController();  
 $lead_query = db_query("select * from orders where id=".$_REQUEST['lead_id']);
 $row_data = db_fetch_array($lead_query);

 $orderDetails = $update_payment->getOrderDetailsById($_REQUEST['lead_id']);

 $points_date=week_range(date('Y-m-d'));
 

                if(isset($_POST['stage'])){                  
                   
                    if($_POST['stage']=='OEM Billing' && $_POST['quantity'] >=0 ){
                        $res =  db_query("UPDATE orders SET quantity =".$_POST['quantityy'] ." WHERE id=".$_REQUEST['lead_id']);    
                    }
                    
                    if($_POST['stage']=='PO/CIF Issued' && isset($_POST['academic_year'])){
                       $res = db_query("
                                        UPDATE orders 
                                        SET academic_year = '" . $_POST['academic_year'] . "' 
                                        WHERE id = " . $_REQUEST['lead_id']
                                    );    
                    }

                    $stage=db_query("select stage,add_comm,quantity,created_by,partner_close_date,is_opportunity,agreement_type,demo_arranged_schedule,demo_rescheduled_schedule,demo_completed_schedule from orders where id=".$_REQUEST['lead_id']." limit 1");
                    $order_detail=db_fetch_array($stage);
                    

                    if($_REQUEST['self_review']=='yes')
                    {
                        $self='Self Review - ';
                    }


                    // If stage is PO/CIF Issued then save onboarding school details in KRA
                    if($_POST['stage']=='PO/CIF Issued'){
                       $update_payment->saveOnboardingSchoolDetailsWithJson($orderDetails,$_REQUEST['lead_id']);
                    }

                    
                    // "PO/CIF Issued",
                    if(isset($order_detail['is_opportunity']) && $order_detail['is_opportunity'] == 1 && $order_detail['agreement_type'] == 'Fresh' && isset($_REQUEST['stage']) && isset($_REQUEST['lead_id']) && in_array($_REQUEST['stage'],["Billing"])){
                         //die("hi456581");  
                         
                       $update_payment->cloningLead($_REQUEST['lead_id']);
                       $res =  db_query("UPDATE orders SET is_renewal =1 WHERE id=".$_REQUEST['lead_id']);
                     }

                     
                     if($order_detail['stage'] != $_POST['stage']){                         
                         $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['lead_id']."','".$self."Stage','".$order_detail['stage']."','".$_POST['stage']."',now(),'".$_SESSION['user_id']."')");
                     }
                    
                    // $lastSubStage = db_query("select * from lead_sub_stage_log WHERE lead_id=".$_REQUEST['lead_id']." ORDER BY id DESC limit 1");

                    // $getSubStage = db_fetch_array($lastSubStage);
                    
                    // if(isset($order_detail['add_comm'])){
                    //     $dataDescription = "has changed Sub Status from ".$order_detail['add_comm']."(".$order_detail['stage'].") from ".$_REQUEST['substage']."(".$_REQUEST['stage'].") to Data on ".date("F d, Y, h:i A");
                    // }else{
                    //     $dataDescription = NULL;
                    // }

                    // $subStageLogInsert =  db_query("insert into lead_modify_log(`lead_id`,`type`,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['lead_id']."','".$_POST['stage']."','".$_POST['substage']."','".$dataDescription."','".$_SESSION['user_id']."')");
                   
                     if($order_detail['add_comm'] != $_POST['substage']){
                          
                        $subS = $_POST['substage'] != '' ? $_POST['substage'] : 'N/A';
                        $stageRequest = $_POST['stage'] != '' ? $_POST['stage'] : '';
                        $add_comm = $order_detail['add_comm'] != '' ? $order_detail['add_comm'] : 'N/A';
                         $subStageInserting =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`,`stage`) values('".$_REQUEST['lead_id']."','".$self."Sub Stage','".$add_comm."','".$subS."',now(),'".$_SESSION['user_id']."','".$stageRequest."')");
                     }

                     $payment_status = $_POST['substage'] == 'Partial/Credit' ? $_POST['payment_status'] : '';
                    $status=db_query("update orders set stage = '".$_POST['stage']."' ,add_comm='".$_POST['substage']."',partial_payment='".$payment_status."' where id=".$_REQUEST['lead_id']);
                    if($_POST['substage'] == 'Demo Arranged'){
                         //die("helo");  
                        $oldRem = $order_detail['demo_arranged_schedule'] ?? 'N/A';
                        $newRem = $_POST['demo_datetime'] ?? 'N/A';
                        if ($oldRem != $newRem) {
                            $demoRem =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`) values('".$_REQUEST['lead_id']."','Demo Arranged Schedule','".$oldRem."','".$newRem."',now(),'".$_SESSION['user_id']."')");
                        }
                        $demo=db_query("update orders set demo_arranged_schedule = '".$_POST['demo_datetime']."' where id=".$_REQUEST['lead_id']);
                    }else if($_POST['substage'] == 'Demo Rescheduled'){
                        // die("what");  
                        $oldRem = $order_detail['demo_rescheduled_schedule'] ?? 'N/A';
                        $newRem = $_POST['demo_datetime'] ?? 'N/A';
                        if ($oldRem != $newRem) {
                            $demoRem =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`) values('".$_REQUEST['lead_id']."','Demo Rescheduled Schedule','".$oldRem."','".$newRem."',now(),'".$_SESSION['user_id']."')");
                        }
                        $demo=db_query("update orders set demo_rescheduled_schedule = '".$_POST['demo_datetime']."' where id=".$_REQUEST['lead_id']);
                    }else if($_POST['substage'] == 'Demo Completed' && $_POST['demo_datetime']){
                        // die("hutt");  
                        $oldRem = $order_detail['demo_completed_schedule'] ?? 'N/A';
                        $newRem = $_POST['demo_datetime'] ?? 'N/A';
                        if ($oldRem != $newRem) {
                            $demoRem =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`) values('".$_REQUEST['lead_id']."','Demo Completed Schedule','".$oldRem."','".$newRem."',now(),'".$_SESSION['user_id']."')");
                        }
                        $demo=db_query("update orders set demo_completed_schedule = '".$_POST['demo_datetime']."' where id=".$_REQUEST['lead_id']);
                    }
                     //die("rahul chugh");  
	                $review=db_query("delete from lead_review where lead_id=".$_REQUEST['lead_id']);

                    if($status){
                       echo 'success';
                    }else{
                        echo 'Error :'. mysqli_info();
                    }


                }
