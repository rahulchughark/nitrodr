<?php
include('includes/include.php');
    if ($_POST['product_code']) {
        if (!empty($_FILES["user_attachment"]["name"])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            if ($_FILES["user_attachment"]["size"] > 4000000) {
                echo "<script>alert('Sorry, your file is too large!')</script>";
                redir("manage_orders.php", true);
            } else {
                move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
            }
        }else{
            $target_file = '';
        }
        
        $number = count($_POST["product_code"]);
        // print_r($_POST);die;
        for ($i = 0; $i < $number; $i++) {
            if($_POST['sales_price'][$i] != $_POST['original_price'][$i]){
                $is_negotiated = 1;
            }else{
                $is_negotiated = 0;
            }
            $originalSalesPrice = $_POST['original_price'][$i];
            $res =  db_query("INSERT INTO tbl_lead_product_opportunity(`lead_id`, `product`, `main_product_id`,`unit_price`,`quantity`,`total_price`,`is_negotiated`,`original_sales_price`) VALUES ('" . $_POST['pid'] . "','" . $_POST['product_code'][$i] . "','" . $_POST['main_product'][$i] . "','" . $_POST['sales_price'][$i] . "','" . $_POST['quantity'][$i] . "','" . $_POST['total_price'][$i] . "','" . $is_negotiated . "','" . $originalSalesPrice . "')");
        }

        $log = db_query("INSERT INTO lead_modify_log(`lead_id`, `type`, `previous_name`, `modify_name`, `created_date`, `created_by`)
                  VALUES('" . $_POST['pid'] . "', 'Opportunity', 'Lead', 'Opportunity', NOW(), '".$_SESSION['user_id']."')");
        $log = db_query("INSERT INTO lead_modify_log(`lead_id`, `type`, `previous_name`, `modify_name`, `created_date`, `created_by`)
                  VALUES('" . $_POST['pid'] . "', 'Stage', 'Opportunity Converstion', 'Quote', NOW(), '".$_SESSION['user_id']."')");

        $inss = db_query("UPDATE orders SET is_opportunity = 1, stage = '".$_POST['opportunity_stage']."', add_comm='' ,expected_close_date = '".$_POST['close_date']."', user_attachement = '".$target_file."', grand_total_price = '".$_POST['grand_total']."' , opportunity_by = '".$_SESSION['user_id']."',product_remarks = '".$_POST['product_remarks']."' WHERE id =".$_POST['pid']);
        if ($inss) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }
        echo json_encode($response);
        // return 'success';
    }