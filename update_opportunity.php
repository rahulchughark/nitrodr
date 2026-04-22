<?php
include('includes/include.php');
// print_r($_POST);die;
    if ($_POST['product_code']) {
        if($_POST['financial_year']){
            list($financial_year_start, $financial_year_end) = explode("-", $_POST['financial_year']);
        }else if($_POST['financial_year_start'] != ''){
            $financial_year_start = $_POST['financial_year_start'];
            $financial_year_end = $_POST['financial_year_end'];
        }else{
            $financial_year_start = 'no';
        }
        if($_POST['is_renew'] == 'yes'){
            $delPro = db_query("UPDATE tbl_lead_product_opportunity SET status = 2 ,deleted_at=now(),deleted_by=".$_SESSION['user_id']." WHERE status=1 AND lead_id =".$_POST['pid']);
        }else{
            $toDelPro = getSingleResult("SELECT GROUP_CONCAT(id) AS ids FROM tbl_lead_product_opportunity where status = 1 and lead_id =".$_POST['pid']);
            $toDelProArr = explode(',', $toDelPro);
            // print_r($toDelProArr);die;
            $delPro = db_query("UPDATE tbl_lead_product_opportunity SET status = 0 ,deleted_at=now(),deleted_by=".$_SESSION['user_id']." WHERE status=1 AND lead_id =".$_POST['pid']);
        }
        if($_POST['is_renew'] == 'yes'){
            $stageOld = getSingleResult("SELECT stage FROM orders where id=".$_POST['pid']);
            $substageOld = getSingleResult("SELECT add_comm FROM orders where id=".$_POST['pid']);
            $progStartDateOld = getSingleResult("SELECT program_start_date FROM orders where id=".$_POST['pid']);
            $oldCheck = getSingleResult("SELECT id FROM tbl_renewal_lead_task_process_record where lead_id=".$_POST['pid']." order by id desc limit 1");
            if($oldCheck > 0){
                $updateOld = db_query("UPDATE tbl_renewal_lead_task_process_record SET stage='".$stageOld."',sub_stage='".$substageOld."',program_start_date='".$progStartDateOld."' WHERE id =".$oldCheck);
            }
            $res =  db_query("INSERT INTO tbl_renewal_lead_task_process_record(`lead_id`, `financial_year_start`, `financial_year_end`) VALUES ('" . $_POST['pid'] . "',".$financial_year_start.",".$financial_year_end.")");
            $renewalLeadTaskProcessId = getSingleResult("select id from tbl_renewal_lead_task_process_record order by id desc limit 1");
            $logg =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_POST['pid']."','Stage','". $stageOld."','N/A',now(),'".$_SESSION['user_id']."')");
            $stageBlank = db_query("UPDATE orders SET stage = '',add_comm = '' WHERE id =".$_POST['pid']);

        }else{
            $renewalLeadTaskProcessId = 0;
        }
        $number = count($_POST["product_code"]);

        for ($i = 0; $i < $number; $i++) {
            if($_POST['sales_price'][$i] != $_POST['original_price'][$i]){
                $is_negotiated = 1;
            }else{
                $is_negotiated = 0;
            }
            $originalSalesPrice = $_POST['original_price'][$i];
            if($financial_year_start == 'no'){
                $res =  db_query("INSERT INTO tbl_lead_product_opportunity(`lead_id`, `product`, `main_product_id`,`unit_price`,`quantity`,`total_price`,`is_negotiated`,`original_sales_price`,renewal_tasks_process_id,upsell) VALUES ('" . $_POST['pid'] . "','" . $_POST['product_code'][$i] . "','" . $_POST['main_product'][$i] . "','" . $_POST['sales_price'][$i] . "','" . $_POST['quantity'][$i] . "','" . $_POST['total_price'][$i] . "','" . $is_negotiated . "','" . $originalSalesPrice . "'," . $renewalLeadTaskProcessId . ",".$_POST['upsell'][$i].")");
            }else{
                $res =  db_query("INSERT INTO tbl_lead_product_opportunity(`lead_id`, `product`, `main_product_id`,`unit_price`,`quantity`,`total_price`,`is_negotiated`,`original_sales_price`,financial_year_start,financial_year_end,renewal_tasks_process_id,upsell) VALUES ('" . $_POST['pid'] . "','" . $_POST['product_code'][$i] . "','" . $_POST['main_product'][$i] . "','" . $_POST['sales_price'][$i] . "','" . $_POST['quantity'][$i] . "','" . $_POST['total_price'][$i] . "','" . $is_negotiated . "','" . $originalSalesPrice . "',".$financial_year_start.",".$financial_year_end."," . $renewalLeadTaskProcessId . ",".$_POST['upsell'][$i].")");
            }
            $resId = get_insert_id();
            if($toDelProArr[$i]){
                $updateOppAtt = db_query("UPDATE opportunity_attachments SET tbl_lead_product_id = '".$resId."' WHERE tbl_lead_product_id =".$toDelProArr[$i]);
            }
        }

        if($_POST['financial_year']){
            $inss = db_query("UPDATE orders SET grand_total_price = '".$_POST['grand_total']."',product_remarks = '".$_POST['product_remarks']."',renew_opportunity_date=now() WHERE id =".$_POST['pid']);

        }else{
                $inss = db_query("UPDATE orders SET grand_total_price = '".$_POST['grand_total']."',product_remarks = '".$_POST['product_remarks']."' WHERE id =".$_POST['pid']);
        }

        if ($inss) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }
        echo json_encode($response);die;
        // return 'success';
    }