<?php include("includes/include.php");
session_start();

class DataController
{
    /**
     * Insert data
     * @param array $users
     * @return array $users
     */

    public function insert(array $data, $tableName)
    {
        $values = [];

        foreach ($data as $key) {
            $values[] = "'" . $key . "'";
        }

        $fields = implode(",", array_keys($data));

        $values = implode(",", $values);

        $result = db_query("INSERT INTO `$tableName` ($fields) VALUES ($values)");
        return $result;
    }

    public function update(array $fields,$table_name, $where_condition){
        
        $query = '';  
        $condition = '';  
        foreach($fields as $key => $value)  
        {  
             $query .= $key . "='".$value."', ";  
        }  
        $query = substr($query, 0, -2);  
        foreach($where_condition as $key => $value)  
        {  
             $condition .= $key . "='".$value."' AND ";  
        }  
        $condition = substr($condition, 0, -5);  
  
        $query = db_query("UPDATE ".$table_name." SET ".$query." WHERE ".$condition."");  
        return $query;
   
    }

    public function select($table_name,$where)
    {
        $condition = '';  
        $array = array();  
        foreach($where as $key => $value)  
        {  
             $condition .= $key . " = '".$value."' AND ";  
        }  
        $condition = substr($condition, 0, -5);  
        $query = "SELECT * FROM ".$table_name." WHERE " . $condition;  
        $result = db_query($query);  
        while($row = db_fetch_array($result))  
        {  
             $array[] = $row;  
        }  
        return $array;

    }


    public function getDataValues($leadID,$subStage){
        //$lastSubStage = db_query("select timestamp from lead_modify_log WHERE lead_id=".$leadID." AND type="."Sub Stage"."  ORDER BY id DESC limit 1");
        $query = db_query("select timestamp FROM lead_modify_log WHERE lead_id = ".$leadID." and modify_name = '".$subStage."' and type='Sub Stage' ORDER BY id DESC LIMIT 1");

        $subStageData = db_fetch_array($query);
        if(isset($subStageData['timestamp'])){
            return date('d-m-Y',strtotime($subStageData['timestamp']));
        }else{
            return 'NA';
        }
    }



    public function cloningLead($parentID){

        $nextYearCloseDate = new DateTime();
        $nextYearCloseDate->modify('+365 day');
        $nextYearCloseDate->format('Y-m-d');
      
        $closeDate = $nextYearCloseDate->format('Y-m-d');
      
        $query = db_query("insert into orders (`parent_id`,`r_name`, `r_email`, `r_user`, `lead_status`, `status`, `source`, `sub_lead_source`, `billing_reseller`,`credit_reseller`, `school_name`, `is_group`, `group_name`, `spoc`, `address`, `state`, `city`, `region`,`country`, `pincode`, `contact`, `website`, `school_email`, `annual_fees`, `eu_name`, `eu_mobile`, `eu_email`,`eu_person_name1`, `eu_designation1`, `eu_mobile1`, `eu_email1`, `eu_person_name2`, `eu_mobile2`, `eu_email2`,`adm_name`, `adm_designation`, `adm_email`, `adm_mobile`, `adm_alt_mobile`, `school_board`, `program_start_date`,`academic_start_date`, `academic_end_date`, `grade_signed_up`, `quantity`, `purchase_no`, `application_date`,`purchase_deails`, `license_period`, `is_app_erp`, `ip_address`, `labs_count`, `system_count`, `os`, `student_system_ratio`,`lab_teacher_ratio`, `standalone_pc`, `projector`, `tv`, `smart_board`, `internet`, `networking`, `thin_client`, `n_computing`,`created_by`,`created_date`,`team_id`,`tag`,`visit_remarks`,`agreement_type`,`expected_close_date`,`is_opportunity`)
        
        select ".$parentID.",r_name, r_email, r_user, lead_status, 'Pending', source, sub_lead_source, billing_reseller,credit_reseller, school_name, is_group, group_name, spoc, address, state, city, region,country, pincode, contact, website, school_email, annual_fees, eu_name, eu_mobile, eu_email,eu_person_name1, eu_designation1, eu_mobile1, eu_email1, eu_person_name2, eu_mobile2, eu_email2,adm_name, adm_designation, adm_email, adm_mobile, adm_alt_mobile, school_board, program_start_date,academic_start_date, academic_end_date, grade_signed_up, quantity, purchase_no, application_date,purchase_deails, license_period, is_app_erp, ip_address, labs_count, system_count, os, student_system_ratio,lab_teacher_ratio, standalone_pc, projector, tv, smart_board, internet, networking, thin_client, n_computing,created_by,now(),team_id,tag,visit_remarks,'Renewal','".$closeDate."',1 from orders WHERE id=".$parentID."");

        $lastInsertedID = get_insert_id();

        $this->cloningLeadProduct($parentID,$lastInsertedID);


    }


    public function cloningLeadProduct($parentID,$childID){
      
        $query = db_query("insert into tbl_lead_product_opportunity (`lead_id`,`main_product_id`,`product`, `unit_price`,`quantity`,`additional_count_req`,`total_price`,`created_at`)        
        select ".$childID.",main_product_id,product, unit_price, quantity, additional_count_req, total_price,now() from tbl_lead_product_opportunity WHERE lead_id=".$parentID." and status=1");
        $finQuery = db_query("UPDATE tbl_lead_product_opportunity t JOIN orders o ON t.lead_id = o.id SET t.financial_year_start = CASE WHEN MONTH(o.program_start_date) >= 4 THEN YEAR(o.program_start_date) ELSE YEAR(o.program_start_date) - 1 END, t.financial_year_end = CASE WHEN MONTH(o.program_start_date) >= 4 THEN YEAR(o.program_start_date) + 1 ELSE YEAR(o.program_start_date) END WHERE o.id=".$childID." and o.agreement_type='Renewal' and o.is_opportunity=1 and (t.financial_year_start IS NULL OR t.financial_year_end IS NULL)");
    }



   public function whatsAppInviteTemplate($sendByUserID,$sendToPhone,$leadID){

    // $sendToPhone = 7065846828;
    $checkInvitation = db_query("select * from whatsapp_messages where user_id = ".$sendByUserID." and lead_id = ".$leadID." and phone = ".$sendToPhone." and is_inivite = 1 ");



        if(mysqli_num_rows($checkInvitation) > 0){
            exit;
        // $templateInit = new DataController;
        // $templateInit->whatsAppInviteTemplate($sendByUserID,$sendToRequest,$leadID);
        }

    
        $url = "https://media.smsgupshup.com/GatewayAPI/rest";

        $params = [
            "userid"          => "2000249837",
            "password"        => "*uh7P4*2",
            "send_to"         => "7065846828", // Recipient's WhatsApp number
            "v"               => "1.1",
            "format"          => "json",
            "msg_type"        => "IMAGE",
            "method"          => "SENDMEDIAMESSAGE",
            "caption"         => "Hi Rahul Chugh,\n\nPlease find below order status Test",
            "media_url"       => "https://ict360.com/ict-new/public/Front/img/ict-logo.png",
            "isTemplate"      => "true",
            "buttonUrlParam"  => "https://ict360.com/"
        ];


        $queryString = http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?" . $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch);
            } else {
                $inviteTemplate = $url.'?'.$queryString;
                $this->whatsAppInviteResponse($sendByUserID,$sendToPhone,$leadID,$response,$inviteTemplate);
                // echo "Response: " . $response;
            }

        curl_close($ch);


   }


   public function whatsAppInviteResponse($sendByUserID,$sendToPhone,$leadID,$gupshupResponse,$inviteTemplate){
        
        $query =  db_query("INSERT INTO `whatsapp_messages`(`user_id`,`phone`,`lead_id`,`is_inivite`,`gupshup_response`,`message`) 
                          VALUES ('" . $sendByUserID . "','" . $sendToPhone . "','" . $leadID . "',1,'".$gupshupResponse."','".$inviteTemplate."')");

   }



   public function checkInvitationSent($user_id,$lead_id,$phone_no){


    $checkInvitation = db_query("select * from whatsapp_messages where user_id = ".$user_id." and lead_id = ".$lead_id." and phone = '".$phone_no."' and is_inivite = 1");

    return mysqli_num_rows($checkInvitation);

   }


   public function getWhatsappNotificationCount($mobileNo) {
    $userID = $_SESSION['user_id'];
    $result = db_query("
        SELECT COUNT(DISTINCT wn.id) AS total
        FROM whatsapp_notification wn
        LEFT JOIN order_important_person AS oi ON wn.mobile = oi.eu_mobile
        LEFT JOIN orders o 
            ON (o.id = oi.order_id OR wn.mobile = o.eu_mobile)
        WHERE o.created_by = $userID
          AND wn.seen = 0
          AND wn.mobile = '$mobileNo'
    ");

    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

  
  public function checkKRATargetExists($user_id, $type, $key_id, $key_subject, $returnType = 'bool') {

        $checkQuery = db_query("SELECT target FROM `kra_users` 
                                WHERE user_id = '$user_id' AND status = 1
                                AND type = '$type' 
                                AND key_id = '$key_id' 
                                AND key_subject = '$key_subject'
                                AND MONTH(date) = MONTH(CURDATE())
                                AND YEAR(date) = YEAR(CURDATE())");

        if ($returnType === 'bool') {
            return mysqli_num_rows($checkQuery) > 0;
        } elseif ($returnType === 'target') {
            if (mysqli_num_rows($checkQuery) > 0) {
                $row = mysqli_fetch_assoc($checkQuery);
                return $row['target'];
            } else {
                return null; 
            }
        }

        return false; 

}

     public function checkKRATargetExistsByUser($user_id) {
            // Query to check if any KRA target exists for the user in the current month and year
            $checkQuery = db_query("SELECT target FROM `kra_users` 
                                    WHERE user_id = '$user_id' 
                                    AND status = 1 
                                    AND MONTH(date) = MONTH(CURDATE()) 
                                    AND YEAR(date) = YEAR(CURDATE())");

            // Return true if at least one record exists, otherwise false
            return mysqli_num_rows($checkQuery) > 0;
        }


    public function getKRATargetsByUser($user_id,$month = NULL) {

        $filterMonth = $month ? $month : date('m');
        // Query to get all KRA targets for the user in the current month and year
        $query = db_query("SELECT * FROM `kra_users` 
                           WHERE user_id = '$user_id' 
                           AND status = 1 
                           AND MONTH(date) = $filterMonth 
                           AND YEAR(date) = YEAR(CURDATE()) ORDER BY type ASC");

        // Fetch all records into an array
        $results = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $results[] = $row;
        }

        return $results; // Returns an array of rows
     }

    // public function getSubStageStatusCountByLeadAndUser($lead_id, $created_by) {
    //     // Query to fetch the latest sub stage entry for a specific lead and creator
    //     $query = db_query("SELECT * FROM lead_modify_log 
    //                        WHERE type = 'Sub Stage' 
    //                        AND lead_id = '$lead_id' 
    //                        AND created_by = '$created_by' 
    //                        ORDER BY lead_id DESC 
    //                        LIMIT 1");

    //     // Return the result as an associative array
    //     return mysqli_fetch_assoc($query);
    // }


    //  public function getSubStageStatusCountByLeadAndUser($created_by, $type = Null,$modify_name) {
    //     // Build the query to count latest entries per lead_id
    //    $kraType = [1 => 'Call Log', 2 => 'Lead Status', 3 => 'Stage', 4 => 'Follow Up', 5 => 'Quote'];

    //    $leadType = $kraType[$type]; 

    //     $query = db_query("
    //         SELECT COUNT(*) AS total
    //         FROM (
    //             SELECT MAX(id)
    //             FROM lead_modify_log               
    //               Where created_by = '$created_by' AND type = '$leadType' AND modify_name = '$modify_name'
    //             GROUP BY lead_id
    //         ) AS latest_logs
    //     ");

    //     $result = mysqli_fetch_assoc($query);
    //     return isset($result['total']) ? (int)$result['total'] : 0;
    // }

    public function getSubStageStatusCountByLeadAndUser($created_by, $type = null, $modify_name, $month = null) {
                        // Map krType to actual type strings
                        $kraType = [1 => 'Call Log', 2 => 'Lead Status', 3 => 'Stage', 4 => 'Follow Up', 5 => 'Quote'];

                        // Validate type
                        if (!isset($kraType[$type])) {
                            return 0;
                        }

                        $leadType = $kraType[$type];

                        // Conditional stage filter based on type
                        $stageFilter = '';
                        if ($type == 4) {
                            $leadType = "sub stage";
                            $stageFilter = "AND `stage` = 'follow-up'";
                        } elseif ($type == 5) {
                            $leadType = "sub stage";
                            $stageFilter = "AND `stage` = 'quote'";
                        }

                        // Apply month filter (always current year)
                        $filterMonth = $month ? $month : date('m');
                        $filterYear  = date('Y');

                        $monthFilter = "AND MONTH(timestamp) = '$filterMonth' AND YEAR(timestamp) = '$filterYear'";

                        // Query
                        $query = db_query("
                            SELECT COUNT(*) AS total
                            FROM (
                                SELECT MAX(id)
                                FROM lead_modify_log               
                                WHERE created_by = '$created_by'
                                AND type = '$leadType'
                                AND modify_name = '$modify_name'
                                $stageFilter
                                $monthFilter
                                GROUP BY lead_id
                            ) AS latest_logs
                        ");

                        $result = mysqli_fetch_assoc($query);
                        return isset($result['total']) ? (int)$result['total'] : 0;
                    }



         public function getCallLogKRACountByUser($created_by, $modify_name) {                   

                      // Current month filter (assumes 'timestamp' is the datetime column)
                    $monthFilter = "AND DATE_FORMAT(created_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";


                    $query = db_query("
                        SELECT COUNT(*) AS total
                        FROM (
                            SELECT MAX(id)
                            FROM activity_log               
                            WHERE added_by = '$created_by'
                              AND call_subject = '$modify_name'                            
                              AND activity_type = 'Lead'                            
                              $monthFilter
                            GROUP BY pid
                        ) AS latest_logs
                    ");


                    $result = mysqli_fetch_assoc($query);
                    return isset($result['total']) ? (int)$result['total'] : 0;
        }


   
   public function getAllLeadStatusNames() {
    $sql = db_query("SELECT * FROM lead_status_master WHERE status = 1 ORDER BY id ASC");
    
    $statusNames = [];
    while($data = db_fetch_array($sql)) {
        $statusNames[] = $data['name'];
    }

    return $statusNames;
    }


    public function getTodayModifyNamesByUserIdKRA($user_id, $fromTime = Null, $toTime  = Null,$isLeadIdsOnly = false) {

        $fTime = $fromTime ? $fromTime : date('Y-m-d');
        $tTime = $toTime ? $toTime : date('Y-m-d');

        // Build dynamic condition
        $userCondition = $user_id != 0 ? "AND created_by = '$user_id'" : "";

       $sql = db_query("SELECT modify_name, type, stage, lead_id
                     FROM lead_modify_log 
                     WHERE created_date >= '$fTime 00:00:00' 
                       AND created_date <= '$tTime 23:59:59'
                       AND type IN ('Stage','Lead Status','Sub Stage','Sub-Stage')
                       $userCondition
                       AND modify_name IS NOT NULL 
                     ORDER BY type ASC");

                $modifyNames = [];
                $modifyCounts = [];

                $leadIDs = "";

                while ($row = db_fetch_array($sql)) {

                        if($row['type'] == "Sub Stage"){
                        $name = $row['modify_name']." (".$row['stage']." Stage)";
                        }else{  
                        $name = $row['modify_name']." (".$row['type'].")";
                        }

                        $modifyNames[$name] = true; // For uniqueness

                        if (!isset($modifyCounts[$name])) {
                            $modifyCounts[$name] = 0;
                        }

                    $modifyCounts[$name]++;
                    $leadIDs .= $row['lead_id'].",";

                }

    return !$isLeadIdsOnly ? $modifyCounts : implode(',',array_unique(explode(',', $leadIDs)));
    
    // return [
    //     'unique_modify_names' => array_keys($modifyNames),
    //     'modify_name_counts'  => $modifyCounts
    // ];
}


 public function getLeadIDsFromActivity($user_id, $fromTime = Null, $toTime  = Null,$modify_name) {
   

        $fTime = $fromTime ? $fromTime : date('Y-m-d');
        $tTime = $toTime ? $toTime : date('Y-m-d');
       
        $pos = strpos($modify_name, '(');
        $cleanedModifyName = $pos !== false ? trim(substr($modify_name, 0, $pos)) : $modify_name;
       

        // Build dynamic condition
        $userCondition = $user_id != 0 ? "AND created_by = '$user_id'" : "";
       
       $sql = db_query("SELECT  lead_id
                     FROM lead_modify_log 
                     WHERE created_date >= '$fTime 00:00:00' 
                       AND created_date <= '$tTime 23:59:59'
                       AND modify_name = '$cleanedModifyName'
                       AND type IN ('Stage','Lead Status','Sub Stage','Sub-Stage')
                       $userCondition
                       AND modify_name IS NOT NULL 
                     ORDER BY type ASC");

                     

        $lead_ids = [];


        while ($row = db_fetch_array($sql)) {        
               $lead_ids[] = $row['lead_id'];
        }



    return ['count'=>count($lead_ids),'leadIDs'=>implode(',',array_unique($lead_ids))];
    
    // return [
    //     'unique_modify_names' => array_keys($modifyNames),
    //     'modify_name_counts'  => $modifyCounts
    // ];
}


 public function getActivityLeadIDsCallLog($user_id, $fromTime = Null, $toTime  = Null,$call_subject) {
            $fTime = $fromTime ? $fromTime : date('Y-m-d');
            $tTime = $toTime ? $toTime : date('Y-m-d');

            $pos = strpos($call_subject, '(');
            $cleanedCallLog = $pos !== false ? trim(substr($call_subject, 0, $pos)) : $call_subject;

            // Build dynamic condition
            $userCondition = $user_id != 0 ? "AND added_by = '$user_id'" : "";

            $sql = db_query("SELECT call_subject,pid
                             FROM activity_log 
                             WHERE created_date >= '$fTime 00:00:00' 
                               AND created_date <= '$tTime 23:59:59'
                               AND call_subject = '$cleanedCallLog'
                               $userCondition 
                               AND call_subject IS NOT NULL 
                             ORDER BY call_subject ASC");

            $lead_ids = [];
           

            while ($row = db_fetch_array($sql)) {                
                $lead_ids[] = $row['pid'];
            }

            return implode(',',array_unique($lead_ids));
            // return [
            //     'unique_modify_names' => array_keys($modifyNames),
            //     'modify_name_counts'  => $modifyCounts
            // ];
        }


    public function getDailyKRAReportByCallLog($user_id, $fromTime = Null, $toTime  = Null) {
            $fTime = $fromTime ? $fromTime : date('Y-m-d');
            $tTime = $toTime ? $toTime : date('Y-m-d');

            // Build dynamic condition
            $userCondition = $user_id != 0 ? "AND added_by = '$user_id'" : "";

            $sql = db_query("SELECT call_subject
                             FROM activity_log 
                             WHERE created_date >= '$fTime 00:00:00' 
                               AND created_date <= '$tTime 23:59:59'
                               $userCondition 
                               AND call_subject IS NOT NULL 
                             ORDER BY call_subject ASC");

            $modifyNames = [];
            $modifyCounts = [];

            while ($row = db_fetch_array($sql)) {
                
                $name = $row['call_subject']." (Call Log)";
                
                $modifyNames[$name] = true; // For uniqueness

                if (!isset($modifyCounts[$name])) {
                    $modifyCounts[$name] = 0;
                }

                $modifyCounts[$name]++;
            }

            return $modifyCounts;
            // return [
            //     'unique_modify_names' => array_keys($modifyNames),
            //     'modify_name_counts'  => $modifyCounts
            // ];
        }

    public function getLeadIdsBySubStage($created_by, $type = null, $modify_name, $month = null) {
        // Map krType to actual type strings
        $kraType = [1 => 'Call Log', 2 => 'Lead Status', 3 => 'Stage', 4 => 'Follow Up', 5 => 'Quote'];

        // Validate type
        if (!isset($kraType[$type])) {
            return '';
        }

        $leadType = $kraType[$type];

        // Conditional stage filter based on type
        $stageFilter = '';
        if ($type == 4) {
            $leadType = "sub stage";
            $stageFilter = "AND `stage` = 'follow-up'";
        } elseif ($type == 5) {
            $leadType = "sub stage";
            $stageFilter = "AND `stage` = 'quote'";
        }

        // Apply month filter (current year only)
        $filterMonth = $month ? $month : date('m');
        $filterYear  = date('Y');

        $monthFilter = "AND MONTH(timestamp) = '$filterMonth' AND YEAR(timestamp) = '$filterYear'";

        // Query
        $query = db_query("
            SELECT lead_id
            FROM (
                SELECT MAX(id) AS max_id, lead_id
                FROM lead_modify_log
                WHERE created_by = '$created_by'
                AND type = '$leadType'
                AND modify_name = '$modify_name'
                $stageFilter
                $monthFilter
                GROUP BY lead_id
            ) AS latest_logs
        ");

        $leadIds = [];
        while ($row = mysqli_fetch_assoc($query)) {
            if (!empty($row['lead_id'])) {
                $leadIds[] = $row['lead_id'];
            }
        }

        return implode(',', array_unique($leadIds));
    }

        // public function getLeadIdsBySubStage($created_by, $type = null, $modify_name) {
        //     // Map krType to actual type strings
        //     $kraType = [1 => 'Call Log', 2 => 'Lead Status', 3 => 'Stage', 4 => 'Follow Up', 5 => 'Quote'];

        //     // Validate type
        //     if (!isset($kraType[$type])) {
        //         return '';
        //     }

        //     $leadType = $kraType[$type];

        //     // Conditional stage filter based on type
        //     $stageFilter = '';
        //     if ($type == 4) {
        //         $leadType = "sub stage";
        //         $stageFilter = "AND `stage` = 'follow-up'";
        //     } elseif ($type == 5) {
        //         $leadType = "sub stage";
        //         $stageFilter = "AND `stage` = 'quote'";
        //     }

        //     // Current month filter
        //     $monthFilter = "AND DATE_FORMAT(timestamp, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";

        //     $query = db_query("
        //         SELECT lead_id
        //         FROM (
        //             SELECT MAX(id) AS max_id, lead_id
        //             FROM lead_modify_log
        //             WHERE created_by = '$created_by'
        //               AND type = '$leadType'
        //               AND modify_name = '$modify_name'
        //               $stageFilter
        //               $monthFilter
        //             GROUP BY lead_id
        //         ) AS latest_logs
        //     ");

        //     $leadIds = [];
        //     while ($row = mysqli_fetch_assoc($query)) {
        //         if (!empty($row['lead_id'])) {
        //             $leadIds[] = $row['lead_id'];
        //         }
        //     }

        //     return implode(',', array_unique($leadIds));
        // }




        public function getCallLogKRALeadIDsByUser($created_by, $modify_name) {
            // Filter for current month (assumes 'created_date' is datetime column)
            $monthFilter = "AND DATE_FORMAT(created_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";

            // Query to get the latest log entries per pid
            $query = db_query("
                SELECT pid
                FROM (
                    SELECT MAX(id) AS max_id, pid
                    FROM activity_log
                    WHERE added_by = '$created_by'
                      AND call_subject = '$modify_name'
                      AND activity_type = 'Lead'
                      $monthFilter
                    GROUP BY pid
                ) AS latest_logs
            ");

            // Collect all pid values
            $pids = [];
            while ($row = mysqli_fetch_assoc($query)) {
                if (!empty($row['pid'])) {
                    $pids[] = $row['pid'];
                }
            }

            // Return comma-separated string
            return implode(',', array_unique($pids));
        }




        // public function insertReminderLog($request,$user_id) {

        //     $leadID        = $request['pid'] ?? null;
        //     $subject       = $request['call_subject'] ?? '';
        //     $remarks       = $request['remarks'] ?? '';
        //     $reminder      = $request['reminder'] ?? 0;
        //     $reminderDate  = $request['reminder_date'] ?? null;
        //     $reminderTime  = $request['reminder_time'] ?? null;
        //     $last_activity_log_id  = $request['last_activity_log_id'] ?? null;
        //     $fullRequest   = json_encode($request, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?? '{}';

        //     $query = db_query("INSERT INTO `activity_log_reminder` 
        //         (`user_id`,`lead_id`, `subject`, `remarks`, `reminder`, `reminder_date`, `reminder_time`, `mail_sent`, `full_request`,`activity_log_id`) 
        //         VALUES 
        //         ('$user_id','$leadID', '$subject', '$remarks', '$reminder', '$reminderDate', '$reminderTime', 0, '$fullRequest','$last_activity_log_id')");

        //     return $query ? true : false;

        // }

// updated by virendra

    public function insertReminderLog($request, $user_id) {

        // 👇 Use your existing mysqli connection here
        // If your project stores it elsewhere, replace $conn accordingly.
        $conn = $GLOBALS['dbcon']; // e.g., $this->conn or get_mysqli()

        // Raw inputs
        $leadID       = $request['pid'] ?? null;
        $subject      = $request['call_subject'] ?? '';
        $remarks1     = $request['remarks'] ?? '';
        // (Optional) your original replacement; you may remove this to keep punctuation
        $remarks      = str_replace(["'", ","], " ", $remarks1);

        $reminder     = $request['reminder'] ?? 0;
        $reminderDate = $request['reminder_date'] ?? null;
        $reminderTime = $request['reminder_time'] ?? null;
        $activityId   = $request['last_activity_log_id'] ?? null;
        $fullRequest  = json_encode($request, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';

        // Type-safe ints / NULLs (no quotes for numeric/NULL)
        $userIdSql    = (int)$user_id;
        $leadIdSql    = isset($leadID) ? (int)$leadID : 'NULL';
        $reminderSql  = (int)$reminder;
        $actIdSql     = isset($activityId) ? (int)$activityId : 'NULL';

        // Escape strings and wrap in quotes
        $subjectSql   = "'" . mysqli_real_escape_string($conn, (string)$subject) . "'";
        $remarksSql   = "'" . mysqli_real_escape_string($conn, (string)$remarks) . "'";
        $fullSql      = "'" . mysqli_real_escape_string($conn, (string)$fullRequest) . "'";

        // Dates/times: quote if present else NULL
        $dateSql = !empty($reminderDate)
            ? "'" . mysqli_real_escape_string($conn, (string)$reminderDate) . "'"
            : "NULL";
        $timeSql = !empty($reminderTime)
            ? "'" . mysqli_real_escape_string($conn, (string)$reminderTime) . "'"
            : "NULL";

        $sql = "INSERT INTO `activity_log_reminder`
                (`user_id`,`lead_id`, `subject`, `remarks`, `reminder`, `reminder_date`, `reminder_time`, `mail_sent`, `full_request`, `activity_log_id`)
                VALUES
                ($userIdSql, $leadIdSql, $subjectSql, $remarksSql, $reminderSql, $dateSql, $timeSql, 0, $fullSql, $actIdSql)";

        $query = db_query($sql);
        return $query ? true : false;
    }


//      public function updateReminderLog($request, $user_id) {

//             $leadID       = $request['leadID'] ?? null;
//             $subject      = $request['call_subject'] ?? '';
//             $remarks      = $request['remarks'] ?? '';
//             $reminder     = $request['reminder'] ?? 0;
//             $reminderDate = $request['reminder_date'] ?? null;
//             $reminderTime = $request['reminder_time'] ?? null;
//             $last_activity_log_id = $request['last_activity_log_id'] ?? null;
//             $fullRequest  = json_encode($request, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?? '{}';

//             if (!$leadID || !$last_activity_log_id) {
//                 return false; // Ensure both keys are available before updating
//             }

//             $query = db_query("UPDATE `activity_log_reminder` SET 
//                         `subject` = '$subject',
//                         `remarks` = '$remarks',
//                         `reminder` = '$reminder',
//                         `reminder_date` = '$reminderDate',
//                         `reminder_time` = '$reminderTime',
//                         `full_request` = '$fullRequest'
//                       WHERE 
//                         `lead_id` = '$leadID' 
//                         AND `activity_log_id` = '$last_activity_log_id'");

//             return $query ? true : false;
// }


        public function updateReminderLog($request, $user_id) {

        $leadID       = $request['leadID'] ?? null;
        $subject      = $request['call_subject'] ?? '';
        $remarks      = $request['remarks'] ?? '';
        $reminder     = $request['reminder'] ?? 0;
        $reminderDate = $request['reminder_date'] ?? null;
        $reminderTime = $request['reminder_time'] ?? null;
        $last_activity_log_id = $request['last_activity_log_id'] ?? null;
        $fullRequest  = json_encode($request, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?? '{}';

        if (!$leadID || !$last_activity_log_id) {
            return false; // Ensure both keys are available
        }

        // Check if a matching record exists
        $check = db_query("SELECT id FROM `activity_log_reminder` 
                           WHERE `lead_id` = '$leadID' 
                           AND `activity_log_id` = '$last_activity_log_id' 
                           AND `deleted` = 0");

        if (mysqli_num_rows($check) > 0) {
            // Record exists - perform UPDATE
            $query = db_query("UPDATE `activity_log_reminder` SET 
                        `subject` = '$subject',
                        `remarks` = '$remarks',
                        `reminder` = '$reminder',
                        `reminder_date` = '$reminderDate',
                        `reminder_time` = '$reminderTime',
                        `full_request` = '$fullRequest'
                      WHERE 
                        `lead_id` = '$leadID' 
                        AND `activity_log_id` = '$last_activity_log_id'");
        } else {
            // Record not found - perform INSERT
            $query = db_query("INSERT INTO `activity_log_reminder` 
                        (`user_id`, `lead_id`, `subject`, `remarks`, `reminder`, `reminder_date`, `reminder_time`, `mail_sent`, `full_request`, `activity_log_id`) 
                        VALUES 
                        ('$user_id', '$leadID', '$subject', '$remarks', '$reminder', '$reminderDate', '$reminderTime', 0, '$fullRequest', '$last_activity_log_id')");
        }

        return $query ? true : false;
}




       public function sendReminderMails() {
       
                date_default_timezone_set('Asia/Kolkata');

                $today = date('Y-m-d');
                $now = date('H:i:s',strtotime('+5 minutes'));
                // $now = date('H:i:s');


                // Fetch unsent reminders
                $query = db_query("
                    SELECT ar.*, u.name, u.email, o.school_name 
                    FROM activity_log_reminder ar 
                    LEFT JOIN users u ON ar.user_id = u.id 
                    LEFT JOIN orders o ON ar.lead_id = o.id 
                    WHERE ar.reminder_date = '$today' 
                      AND ar.reminder_time <= '$now' 
                      AND ar.mail_sent = 0 
                      AND ar.user_id != 0
                      AND ar.deleted = 0
                ");


                // echo "<pre>";
                // print_r(mysqli_fetch_assoc($query));
                // exit;
                while ($row = mysqli_fetch_assoc($query)) {
                    
                    // --- Sanitize & Prepare ---
                    $email     = 'rahul.chugh@arkinfo.in'; // Hardcoded for testing, change to: $row['email']
                    $userName  = htmlspecialchars($row['name'], ENT_QUOTES);
                    $subject   = "Reminder: " . htmlspecialchars($row['subject'], ENT_QUOTES)." {$row['reminder_date']} {$row['reminder_time']}";

                    // HTML message body
                    $message = "
                            <p>Dear <strong>{$userName}</strong>,</p>

                            <p>This is a gentle reminder regarding your upcoming meeting:</p>

                            <ul>
                                <li><strong>📌 Subject:</strong> {$row['subject']}</li>
                                <li><strong>🏫 School:</strong> {$row['school_name']}</li>
                                <li><strong>📝 Remarks:</strong> {$row['remarks']}</li>
                                <li><strong>🕒 Scheduled Time:</strong> {$row['reminder_date']} at {$row['reminder_time']}</li>
                            </ul>

                            <p>We appreciate your attention and timely participation.</p>

                            <p>Best regards,<br><strong>ICT DR Support Team</strong></p>

                            <hr style='border: 0; border-top: 1px solid #ccc; margin-top: 30px;' />

                            <p style='color: #a94442; font-size: 12px;'>
                                <strong>⚠ CAUTION:</strong> This email originated from outside of the organization. 
                                Do not click links or open attachments unless you recognize the sender and trust the content.
                            </p>
                        ";

                    $this->realTimeReminderNotificationAlert($row['user_id'],$row['subject'],$row['remarks'],$row['reminder_date'],$row['reminder_time'],$row['school_name']);
                    // --- Send Mail ---
                    $mailSent = sendMailReminder($email, $subject, $message);
                     
                    $mailSent = true;

                    // --- Update Status if sent successfully ---
                    if ($mailSent === true) {
                        $id = (int) $row['id'];
                        db_query("UPDATE activity_log_reminder SET mail_sent = 1 WHERE id = $id");
                    }
                }

                return true;
            }





function realTimeReminderNotificationAlert($user_id,$subject,$remarks,$reminder_date,$reminder_time,$school_name){

         $options = array(
                            'cluster' => 'ap2',
                            'useTLS' => true
                        );

                            $pusher = new Pusher\Pusher(
                                getPusherCredentials('key'),
                                getPusherCredentials('secret'),
                                getPusherCredentials('app_id'),
                                $options
                            );

                            // Fire only if reminder condition matches
                            $data = ['message' => 'Reminder Triggered!',
                                     'user_id'=>$user_id,
                                     'subject'=>ucfirst($subject),
                                     'remarks'=>ucfirst($remarks),
                                     'reminder_date'=>date('d-F-Y',strtotime($reminder_date)),
                                     'reminder_time'=>$reminder_time,
                                     'school_name'=>$school_name
                                    ];
                            $pusher->trigger('reminder-channer-notification', 'reminder-event-notification', $data);

}




            function getCollectionData() {
                // Apply user-level filtering if not ADMIN
                if ($_SESSION['user_type'] == "ADMIN" || $_SESSION['user_type'] == "FM") {
                    $whrCond = "";
                } else {
                    $whrCond = " AND o.created_by = " . intval($_SESSION['user_id']);
                }

                // Query to fetch distinct school IDs and names
                $query = "
                    SELECT DISTINCT 
                    o.id,
                    o.school_name, 
                    o.agreement_type 
                    FROM orders o 
                    WHERE o.school_name IS NOT NULL 
                      AND o.school_name != '' 
                      $whrCond
                     ORDER BY id DESC
                      LIMIT 50
                ";

                return db_query($query);
            }

        // function commented on : 14-oct-2025
        // public function getProductsByLeadId($lead_id, $is_group, $group_name) {
        //     $products = [];

        //     $result = db_query("
        //         SELECT lpo.main_product_id, 
        //                mpo.name AS product_name, 
        //                SUM(lpo.quantity) AS total_quantity,
        //                SUM(lpo.total_price) AS total_price
        //         FROM tbl_lead_product_opportunity AS lpo
        //         LEFT JOIN tbl_main_product_opportunity AS mpo 
        //             ON mpo.id = lpo.main_product_id
        //         WHERE lpo.lead_id = " . intval($lead_id) . " 
        //           AND lpo.deleted_by IS NULL 
        //           AND lpo.status = 1
        //         GROUP BY lpo.main_product_id, mpo.name
        //     ");


        //     while ($row = db_fetch_array($result)) {
        //         $products[] = [
        //              'main_product_id' => $row['main_product_id'],
        //              'product_name'    => $row['product_name'],
        //              'quantity'        => $row['total_quantity'],
        //              'price_inc_gst'   => $row['total_price'] * 18 / 100 + $row['total_price'],
        //              'price_exc_gst'   => $row['total_price'],
        //         ];
        //     }

        //     return $products;
        // }

        public function getProductsByLeadId($lead_id, $is_group, $group_name) {
                                $products = [];

                                if ($is_group === 'yes') {
                                    // Fetch all lead IDs for the group
                                    $leadIdsResult = db_query("
                                        SELECT id 
                                        FROM orders 
                                        WHERE group_name = '" . $group_name . "'
                                    ");

                                    $leadIds = [];
                                    while ($row = db_fetch_array($leadIdsResult)) {
                                        $leadIds[] = intval($row['id']);
                                    }

                                    if (empty($leadIds)) {
                                        return []; // no leads found
                                    }

                                    $leadIdList = implode(',', $leadIds);

                                    // Fetch product data for all leads in the group
                                    $query = "
                                        SELECT lpo.main_product_id, 
                                            mpo.name AS product_name, 
                                            SUM(lpo.quantity) AS total_quantity,
                                            SUM(lpo.total_price) AS total_price
                                        FROM tbl_lead_product_opportunity AS lpo
                                        LEFT JOIN tbl_main_product_opportunity AS mpo 
                                            ON mpo.id = lpo.main_product_id
                                        WHERE lpo.lead_id IN ($leadIdList)
                                        AND lpo.deleted_by IS NULL 
                                        AND lpo.status = 1
                                        GROUP BY lpo.main_product_id, mpo.name
                                    ";
                                } else {
                                    // Single lead data
                                    $query = "
                                        SELECT lpo.main_product_id, 
                                            mpo.name AS product_name, 
                                            SUM(lpo.quantity) AS total_quantity,
                                            SUM(lpo.total_price) AS total_price
                                        FROM tbl_lead_product_opportunity AS lpo
                                        LEFT JOIN tbl_main_product_opportunity AS mpo 
                                            ON mpo.id = lpo.main_product_id
                                        WHERE lpo.lead_id = " . intval($lead_id) . "
                                        AND lpo.deleted_by IS NULL 
                                        AND lpo.status = 1
                                        GROUP BY lpo.main_product_id, mpo.name
                                    ";
                                }

                                $result = db_query($query);

                                while ($row = db_fetch_array($result)) {
                                    $products[] = [
                                        'main_product_id' => $row['main_product_id'],
                                        'product_name'    => $row['product_name'],
                                        'quantity'        => $row['total_quantity'],
                                        'price_exc_gst'   => $row['total_price'],
                                        'price_inc_gst'   => $row['total_price'] * 1.18, // adds 18% GST
                                    ];
                                }

                                return $products;
                            }


       // Inc & Exc GST Amount Functionn - function commented 14-oct-2025
        // public function getCollectionPrice($lead){

        //        $princeIncGST = 0;
        //        $priceExcGST = 0;


        //        $result = db_query("
        //         SELECT lpo.product, po.tax,lpo.total_price 
        //         FROM tbl_lead_product_opportunity AS lpo
        //         LEFT JOIN tbl_product_opportunity AS po
        //         ON po.id = lpo.product
        //         WHERE lpo.lead_id = $lead AND
        //         lpo.status = 1 AND lpo.deleted_by IS NULL 
        //     ");


        //     while ($row = db_fetch_array($result)) {

        //         $GSTAmount = $row['total_price'] * $row['tax'] / 100 + $row['total_price'];
        //         $NoGSTAmount = $row['total_price'];

        //         $princeIncGST += $GSTAmount;
        //         $priceExcGST += $NoGSTAmount;

        //     }
           

        //     return [
        //             'total_inc_gst'=>number_format($princeIncGST),
        //             'total_exc_gst'=>number_format($priceExcGST)
        //             ];

        // }

        public function getCollectionPrice($lead_id, $is_group = 0, $group_name = null) {
                            $priceIncGST = 0;
                            $priceExcGST = 0;

                            // Step 1: Build lead condition
                            if ($is_group === 'yes') {
                                // Fetch all lead IDs under this group
                                $leadIdsResult = db_query("
                                    SELECT id 
                                    FROM orders 
                                    WHERE group_name = '" . $group_name . "'
                                ");

                                $leadIds = [];
                                while ($row = db_fetch_array($leadIdsResult)) {
                                    $leadIds[] = intval($row['id']);
                                }

                                if (empty($leadIds)) {
                                    return [
                                        'total_inc_gst' => 0,
                                        'total_exc_gst' => 0
                                    ];
                                }

                                $leadIdList = implode(',', $leadIds);
                                $leadCondition = "lpo.lead_id IN ($leadIdList)";
                            } else {
                                $leadCondition = "lpo.lead_id = " . intval($lead_id);
                            }

                            // Step 2: Fetch product opportunity data
                            $result = db_query("
                                        SELECT lpo.product, po.tax, lpo.total_price, mpo.name as product_name
                                        FROM tbl_lead_product_opportunity AS lpo

                                        LEFT JOIN tbl_product_opportunity AS po 
                                            ON po.id = lpo.product

                                        LEFT JOIN tbl_main_product_opportunity AS mpo 
                                            ON mpo.id = lpo.main_product_id

                                        WHERE $leadCondition
                                        AND lpo.status = 1
                                        AND lpo.deleted_by IS NULL");

                            $amount3Ratio = 0;

                            // Step 3: Calculate totals
                            while ($row = db_fetch_array($result)) {
                                $taxRate = floatval($row['tax']);
                                $totalPrice = $row['product_name'] == "Model 3" ?  ($row['total_price'] / 7) * 4 : floatval($row['total_price']);
                                $amount3Ratio += $row['product_name'] == "Model 3" ?  ($row['total_price'] / 7) * 3 : 0;
                                
                                $GSTAmount = $totalPrice * $taxRate / 100 + $totalPrice;
                                $NoGSTAmount = $totalPrice;

                                $priceIncGST += $GSTAmount;
                                $priceExcGST += $NoGSTAmount;
                            }

                            // Step 4: Return formatted results
                            return [
                                'total_inc_gst' => number_format($priceIncGST + $amount3Ratio),
                                'total_exc_gst' => number_format($priceExcGST + $amount3Ratio)
                            ];
                        }


            public function fetchOrderProducts($orderID, $is_group = 0, $group_name = null)
            {
                $orderID = (int) $orderID;
                $is_group = (int) $is_group; // ensure boolean-like

                if ($is_group) {
                    $groupLeadIDs = $this->getGroupOrderIdsStr($group_name);

                    // Ensure it's safe numeric list (like "1,2,3")
                    $groupLeadIDs = preg_replace('/[^0-9,]/', '', $groupLeadIDs);
                    $leadCondition = "lpo.lead_id IN ($groupLeadIDs)";
                } else {
                    $leadCondition = "lpo.lead_id = $orderID";
                }

                $query = "
                    SELECT 
                        lpo.main_product_id,
                        lpo.product,
                        lpo.quantity,
                        mpo.name AS product_mst_name,
                        po.product_name AS sub_product,
                        po.tax AS gst_tax,
                        lpo.total_price,
                        lpo.id AS lead_product_id
                    FROM tbl_lead_product_opportunity AS lpo
                    LEFT JOIN tbl_main_product_opportunity AS mpo ON mpo.id = lpo.main_product_id
                    LEFT JOIN tbl_product_opportunity AS po ON po.id = lpo.product
                    WHERE $leadCondition
                    AND lpo.status = 1
                    AND lpo.deleted_by IS NULL
                    ORDER BY mpo.name ASC
                ";

                return db_query($query);
            }

            public function fetchLeadCustomAmount($lead_id)
                {
                    $lead_id = (int) $lead_id; // ensure safe integer

                    $query = "
                        SELECT 
                            id,
                            lead_id,
                            previous_amount,
                            amount,
                            is_deleted,
                            created_at,
                            updated_at
                        FROM tbl_lead_custom_amount
                        WHERE lead_id = $lead_id
                        AND is_deleted = 0
                        ORDER BY id DESC
                        LIMIT 1
                    ";

                    return db_fetch_array(db_query($query));  
                }


    //   public function fetchOrderProducts($orderID) {
    //             $orderID = (int) $orderID;

    //             return db_query("
    //                 SELECT 
    //                     lpo.main_product_id,
    //                     lpo.product,
    //                     lpo.quantity,
    //                     mpo.name AS product_mst_name,
    //                     po.product_name AS sub_product,
    //                     po.tax AS gst_tax,
    //                     lpo.total_price,
    //                     lpo.id as lead_product_id
    //                 FROM tbl_lead_product_opportunity AS lpo 
    //                 LEFT JOIN tbl_main_product_opportunity AS mpo ON mpo.id = lpo.main_product_id    
    //                 LEFT JOIN tbl_product_opportunity AS po ON po.id = lpo.product      
    //                 WHERE lpo.lead_id = $orderID 
    //                   AND lpo.status = 1 
    //                   AND lpo.deleted_by IS NULL
    //                 ORDER BY product_mst_name ASC
    //             ");
    //         }


            public function fetchOrderAttachments($orderID,$attachment_type = "pi_attachments", $is_group = 0, $group_name = null) {
                            $orderID = (int) $orderID;

                            $is_group = (int) $is_group; // ensure boolean-like

                            if ($is_group) {
                                $groupLeadIDs = $this->getGroupOrderIdsStr($group_name);
                                // Ensure it's safe numeric list (like "1,2,3")
                                $groupLeadIDs = preg_replace('/[^0-9,]/', '', $groupLeadIDs);
                                $leadCondition = "oa.lead_id IN ($groupLeadIDs)";
                            } else {
                                $leadCondition = "oa.lead_id = $orderID";
                            }

                            return db_query("
                                SELECT 
                                    oa.attachment_path,
                                    oa.product_id,
                                    po.product_name,
                                    oa.attachment_name,
                                    oa.id as attachment_id,
                                    oa.lead_id as master_lead_id 
                                FROM opportunity_attachments AS oa
                                LEFT JOIN tbl_product_opportunity AS po 
                                    ON po.id = oa.product_id
                                WHERE $leadCondition
                                AND oa.status = 1
                                AND oa.attachment_type = '$attachment_type'
                                ORDER BY po.product_name ASC
                            ");
                        }

        public  function getInvoiceEmiByOrderId($order_id) {

            $order_id = intval($order_id); // prevent SQL injection

            $sql = "SELECT ie.id,ie.amount, ie.date,ie.received_amount, ie.received_date
                    FROM tbl_invoice_emi as ie 
                    WHERE order_id = $order_id 
                      AND is_deleted = 0 
                    ORDER BY id ASC";
            $result = db_query($sql);

            $data = [];
            while ($row = db_fetch_array($result)) {
                $data[] = $row;
            }

            return $data;

        }

       public function getCurrentMonthInvoiceEmi($order_id) {
                $order_id = (int)$order_id;

                $sql = "SELECT (ie.amount - ie.received_amount) AS pending_amount
                        FROM tbl_invoice_emi AS ie
                        WHERE ie.order_id = $order_id
                        AND ie.is_deleted = 0
                        AND ie.status = 1
                        AND MONTH(ie.date) = MONTH(CURDATE())
                        AND YEAR(ie.date) = YEAR(CURDATE())
                        ORDER BY ie.id ASC
                        LIMIT 1";

                $result = db_query($sql);
                $row = db_fetch_array($result);

                $pendingAmount = isset($row['pending_amount']) ? (float)$row['pending_amount'] : 0;

                return $pendingAmount > 0 ? '₹' . number_format($pendingAmount, 2) : 0;
            }


        public function getTotalOutstandingByOrder($order_id) {
                    $order_id = (int)$order_id;

                    // Get total pending amount
                    $sql = "SELECT SUM(ie.amount - IFNULL(ie.received_amount, 0)) AS pending_amount
                            FROM tbl_invoice_emi AS ie
                            WHERE ie.order_id = $order_id
                            AND ie.is_deleted = 0
                            AND ie.status = 1
                            AND (ie.amount - IFNULL(ie.received_amount, 0)) > 0";

                    $result = db_query($sql);
                    $row = db_fetch_array($result);

                    $pendingAmount = isset($row['pending_amount']) ? (float)$row['pending_amount'] : 0;
                    // return $pendingAmount > 0 ? '₹' . number_format($pendingAmount, 2) : 0;

                    // Get TDS amount from tbl_mst_invoice
                    $tdsSql = "SELECT tds FROM tbl_mst_invoice WHERE order_id = $order_id AND is_deleted = 0 LIMIT 1";
                    $tdsResult = db_query($tdsSql);
                    $tdsRow = db_fetch_array($tdsResult);
                    $tdsAmount = isset($tdsRow['tds']) ? (float)$tdsRow['tds'] : 0;

                    // Subtract TDS from pending amount
                    $finalAmount = $pendingAmount - $tdsAmount;

                    return $finalAmount > 0 ? '₹' . number_format($finalAmount, 2) : 0;
                }



            public function updateTdsAmount($order_id, $tds_amount) {
                $order_id = (int)$order_id; // Ensure integer for security
                $tds_amount = (float)$tds_amount; // Ensure float for decimal values

                $sql = "UPDATE tbl_mst_invoice 
                        SET tds = $tds_amount, updated_at = NOW()
                        WHERE order_id = $order_id 
                        AND is_deleted = 0";

                return db_query($sql); // Returns true/false depending on success
            }



        public function fetchAttachmentQuery($orderId, $product_id, $sub_product_id){
          
        


        }



        public function isInvoiceReady($order_id) {
                $order_id = intval($order_id);
                $sql = "SELECT 1 
                        FROM tbl_invoice_emi 
                        WHERE order_id = $order_id 
                        AND status = 1 
                        AND is_deleted = 0 
                        LIMIT 1";
                $result = db_query($sql);
                return db_num_array($result) > 0; // true if exists, false if not
            }


            function fetchCategoryIdByName($name) {
               
                $result = db_query("
                    SELECT id 
                    FROM categories 
                    WHERE name = '$name' 
                    AND status = 1 
                    AND deleted = 0 
                    LIMIT 1
                ");

                if ($result && $row = mysqli_fetch_assoc($result)) {
                    return (int) $row['id']; // return the ID
                }

                return 0; // return null if no match
            }




           public function isInvoiceReadyWithAttachments($order_id, $attachment_type = null) {
                $order_id = intval($order_id);

                if ($attachment_type === "invoice_attachments") {
                    $sql = "
                        SELECT CASE 
                            WHEN EXISTS (
                                SELECT 1 
                                FROM opportunity_attachments 
                                WHERE lead_id = $order_id 
                                AND status = 1
                                AND attachment_type = 'invoice_attachments'
                            )
                            THEN 1 ELSE 0 
                        END AS is_ready
                    ";
                } else {
                    $attachment_filter = $attachment_type ? "AND attachment_type = '" . $attachment_type . "'" : "";

                    $sql = "
                        SELECT CASE 
                            WHEN EXISTS (
                                SELECT 1 
                                FROM tbl_invoice_emi 
                                WHERE order_id = $order_id 
                                AND status = 1 
                                AND is_deleted = 0
                            )
                            OR EXISTS (
                                SELECT 1 
                                FROM opportunity_attachments 
                                WHERE lead_id = $order_id 
                                AND status = 1
                                $attachment_filter
                            )
                            THEN 1 ELSE 0 
                        END AS is_ready
                    ";
                }

                $result = db_query($sql);
                $row = db_fetch_array($result);
                return $row['is_ready'] == 1;
            }

        // public function getAllCategoriesTree() {
        //     // 1. Get all categories
        //     $sql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 ORDER BY name ASC");

        //     $all = [];
        //     while ($row = db_fetch_array($sql)) {
        //         $all[] = $row;
        //     }

        //     // 2. Group by parent_id
        //     $tree = [];
        //     foreach ($all as $row) {
        //         $tree[$row['parent_id']][] = $row;
        //     }

        //     // 3. Recursive renderer
        //     $renderTree = function($parent_id = 0) use (&$renderTree, $tree) {
        //         if (!isset($tree[$parent_id])) return;

        //         echo $parent_id == 0 ? '<ul class="file-tree">' : '<ul>';

        //         foreach ($tree[$parent_id] as $cat) {
        //             echo '<li class="folder" data-id="' . $cat['id'] . '">';
        //             echo '<div class="fi">' . htmlspecialchars($cat['name']) . '</div>';

        //             // check children
        //             $renderTree($cat['id']);

        //             // 👉 If leaf node => show form
        //             if (!isset($tree[$cat['id']])) {
        //                 echo '<ul>
        //                         <li>
        //                         <div class="content-category">
        //                             <form action="ajax_learning_zone_upload.php" 
        //                                 method="post" 
        //                                 class="upload-form p-t-20" 
        //                                 enctype="multipart/form-data">
                                        
        //                                 <input type="hidden" name="category_id" value="' . $cat['id'] . '">
        //                                 <input type="hidden" name="category_name" value="'.$cat['name'].'"> 

        //                                 <div>
        //                                     <h5 class="font-weight-bold mb-3">Upload Documents</h5> 

        //                                     <div class="form-group">
        //                                         <label>Title</label>
        //                                         <input type="text" name="title" class="form-control" placeholder="title" required />
        //                                     </div>

        //                                     <div class="form-group">
        //                                         <label>Type</label><br>
        //                                         <div class="form-check form-check-inline">
        //                                             <input class="form-check-input" type="radio" name="file_type" id="docType' . $cat['id'] . '" value="DOC" checked>
        //                                             <label class="form-check-label" for="docType' . $cat['id'] . '">DOC</label>
        //                                         </div>
        //                                         <div class="form-check form-check-inline">
        //                                             <input class="form-check-input" type="radio" name="file_type" id="videoType' . $cat['id'] . '" value="VIDEO">
        //                                             <label class="form-check-label" for="videoType' . $cat['id'] . '">VIDEO</label>
        //                                         </div>
        //                                     </div>

        //                                     <div class="form-group">
        //                                         <label for="">Attach Document</label>
        //                                         <div class="upload-container">
        //                                             <div class="drop-area">
        //                                                 <div class="preview"></div>
        //                                                 <p>Drag & Drop files here or <strong>click</strong> to upload</p>
        //                                                 <input name="attachments[]" type="file" 
        //                                                     id="fileElm' . $cat['id'] . '" 
        //                                                     class="fileElem" 
        //                                                     multiple 
                                                            
        //                                                     hidden>
        //                                                 <label class="add-file-btn" for="fileElm' . $cat['id'] . '">Add Files</label>
        //                                             </div>
        //                                         </div>
        //                                     </div>

        //                                     <div class="text-center mt-3">
        //                                         <button class="btn btn-primary">Next</button>
        //                                     </div>
        //                                 </div>

        //                                 <div>
        //                                     <h5 class="font-weight-bold mb-3">Manage Partner Access</h5>
        //                                     <div class="form-group">
        //                                         <label>Partner Name</label>
        //                                         <select name="partner" id="partner" class="multiselect_partner form-control" required data-live-search="true" multiple>
        //                                             <option value="">Teeming Monk</option>
        //                                             <option value="">Edulink consulting</option>
        //                                             <option value="">P P Sales</option>
        //                                             <option value="">Neltas</option>
        //                                         </select>
        //                                     </div>
        //                                     <div class="form-group">
        //                                         <label>User Type</label>
        //                                         <select name="user_type" id="user_type" required class="form-control multiselect_user_type" multiple>
        //                                                 <option value="">User</option>
        //                                                 <option value="">Manager</option>
        //                                         </select>
        //                                     </div>
        //                                     <div class="text-center mt-3">
        //                                         <button type="submit" class="btn btn-primary">Upload</button>
        //                                     </div>
        //                                 </div>

                                        
        //                             </form>
        //                         </div>
        //                         </li>
        //                     </ul>';
        //             }

        //             echo '</li>';
        //         }

        //         echo '</ul>';
        //     };

        //     // 4. Start rendering
        //     $renderTree(0);
        // }


        public function getAllCategoriesTree() {
    // 1. Get all categories
    $sql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 ORDER BY name ASC");

    $all = [];
    while ($row = db_fetch_array($sql)) {
        $all[] = $row;
    }

    // 2. Group by parent_id
    $tree = [];
    foreach ($all as $row) {
        $tree[$row['parent_id']][] = $row;
    }

    // 3. Recursive renderer
    $renderTree = function($parent_id = 0) use (&$renderTree, $tree) {
        if (!isset($tree[$parent_id])) return;

        echo $parent_id == 0 ? '<ul class="file-tree">' : '<ul>';

        foreach ($tree[$parent_id] as $cat) {
            echo '<li class="folder" data-id="' . $cat['id'] . '">';
            echo '<div class="fi">' . htmlspecialchars($cat['name']) . '</div>';

            // check children
            $renderTree($cat['id']);

            //   <div class="form-group">
            //                             <label>Title</label>
            //                             <input type="text" name="title" class="form-control" placeholder="title" required />
            //                         </div>

            // 👉 If leaf node => show form
            if (!isset($tree[$cat['id']])) {
                echo '<ul>
                        <li>
                        <div class="content-category">
                            <form action="ajax_learning_zone_upload.php" 
                                method="post" 
                                class="upload-form p-t-20" 
                                enctype="multipart/form-data">
                                
                                <input type="hidden" name="category_id" value="' . $cat['id'] . '">
                                <input type="hidden" name="category_name" value="'.$cat['name'].'"> 

                                 <div class="manage-permissions" id="permission-container" >
                                    <h5 class="font-weight-bold mb-3">Manage Partner Access</h5>
                                    <div class="form-group">
                                        <label>Partner Name</label>
                                        <select name="partner[]" class="multiselect_partner form-control" required data-live-search="true" multiple>
                                            '.$this->getActivePartners().'
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="user_type[]" class="form-control multiselect_user_type" multiple required>
                                                <option value="USR">User</option>
                                                <option value="MNGR">Manager</option>
                                        </select>
                                    </div>
                                    

                                     <div class="text-center mt-3">
                                        <button type="button" disabled  class="btn btn-primary next-btn btn-next nextBtnFileUploader user_type_permissions_btn">Next</button>
                                    </div>
                                </div>

                                <!-- Upload Section -->
                                <div class="upload-section step-upload" id="document-container" style="display:none;">
                                    <h5 class="font-weight-bold mb-3">
                                        Upload Documents <small class="text-muted">— Add up to 15 files at once</small>
                                    </h5> 

                                    <div class="form-group">
                                        <label>Type</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="file_type" id="docType' . $cat['id'] . '" value="DOC" checked>
                                            <label class="form-check-label" for="docType' . $cat['id'] . '">DOC</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="file_type" id="videoType' . $cat['id'] . '" value="VIDEO">
                                            <label class="form-check-label" for="videoType' . $cat['id'] . '">VIDEO</label>
                                        </div>
                                    </div>

                                    <div class="form-group">

                                    <div class="d-none progress mt-2 tracker-uploader">
                                    <div id="upload-progress-bar" class="progress-bar upload-progress-bar" 
                                        role="progressbar" style="width: 0%;" 
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div id="upload-progress"  class="d-none tracker-uploader upload-progress">0%</div>


                                        <label for="">Attach Document</label>
                                        <div class="upload-container">
                                            <div class="drop-area">
                                                <div class="preview"></div>
                                                <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                                                <input name="attachments[]" type="file" 
                                                    id="fileElm' . $cat['id'] . '" 
                                                    class="fileElem" 
                                                    multiple 
                                                    hidden>
                                                <label class="add-file-btn" for="fileElm' . $cat['id'] . '">Add Files</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="text-center mt-3">
                                        <button type="button" disabled="true" class="btn btn-primary next-btn btn-next nextBtnFileUploader">Next</button>
                                    </div> -->
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-primary final-uploader">Upload</button>
                                    </div>
                                </div>

                                <!-- Manage Partner Section (Hidden by default) -->
                               

                                
                            </form>
                        </div>
                        </li>
                    </ul>';
            }

            echo '</li>';
        }

        echo '</ul>';
    };

    // 4. Start rendering
    $renderTree(0);
}


            function getActivePartners($selectedPartners = []) {
                
                // Check condition based on session
                if ($_SESSION['sales_manager'] != 1) {
                    $res = db_query("SELECT * FROM partners WHERE status='Active'");
                } else {
                    $res = db_query("SELECT * FROM partners WHERE id IN (" . $_SESSION['access'] . ") AND status='Active'");
                }

                // Build options HTML
                $options = '';
                while ($row = db_fetch_array($res)) {
               
                    $selected = in_array($row['id'], $selectedPartners) ? 'selected' : '';
                    $options .= "<option value='{$row['id']}' {$selected}>{$row['name']}</option>";
                }

                return $options;
            }



        public function getCategoryPath($categoryId) {
            $categoryId = (int)$categoryId;
            $path = [];

            while ($categoryId > 0) {
                $sql = "SELECT id, parent_id, name 
                        FROM categories 
                        WHERE id = $categoryId 
                        AND deleted = 0 
                        LIMIT 1";

                $result = db_query($sql);
                $row = db_fetch_array($result);

                if (!empty($row)) {
                    $path[] = $row['name'];
                    $categoryId = (int)$row['parent_id'];
                } else {
                    break;
                }
            }

            if (empty($path)) {
                return '';
            }

            // Reverse path to make it from root -> child
            $path = array_reverse($path);

            // Apply truncation only for parents, keep child full
            $lastIndex = count($path) - 1;
            foreach ($path as $i => &$name) {
                if ($i < $lastIndex && strlen($name) > 3) {
                    $name = substr($name, 0, 3) . "...";
                }
            }

            return implode(' > ', $path);
        }


    //    public function getAllCategoriesTreeUl() {
    //             $searchName = "abc";
    //             $sql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 AND name LIKE '%$searchName%'  ORDER BY name ASC");

    //             $all = [];
    //             while ($row = db_fetch_array($sql)) {
    //                 $all[] = $row;
    //             }

    //             // Group by parent
    //             $tree = [];
    //             foreach ($all as $row) {
    //                 $tree[$row['parent_id']][] = $row;
    //             }

    //             // Recursive builder
    //             $renderTree = function($parent_id = 0) use (&$renderTree, $tree) {
    //                 if (!isset($tree[$parent_id])) return '';

    //                 $html = '';
    //                 foreach ($tree[$parent_id] as $cat) {
    //                     if (isset($tree[$cat['id']])) {
    //                         // Folder with children
    //                         $html .= '<li class="folder 454">';
    //                         // onclick="return showFilterMaterial(\'' . $cat['id'] . '\', \'' . $cat['name'] . '\')"
    //                         $html .= '<div class="fi parent" >' . htmlspecialchars($cat['name']) . '</div>';
    //                         $html .= '<ul>' . $renderTree($cat['id']) . '</ul>';
    //                         $html .= '</li>';
    //                     } else {
    //                         // Leaf category → group siblings under content-category
    //                         static $contentCategoryOpen = false;

    //                         if (!$contentCategoryOpen) {
    //                             $html .= '<li><div class="content-category">';
    //                             $contentCategoryOpen = true;
    //                         }

    //                         $html .= '<div class="fi child" onclick="return showFilterMaterial(\'' . $cat['id'] . '\', \'' . $cat['name'] . '\')">' . htmlspecialchars($cat['name']) . '</div>';

    //                         // Close when last sibling
    //                         $siblings = $tree[$parent_id];
    //                         if ($cat === end($siblings)) {
    //                             $html .= '</div></li>';
    //                             $contentCategoryOpen = false;
    //                         }
    //                     }
    //                 }
    //                 return $html;
    //             };

    //             // Final wrapper
    //             return $renderTree(0);
    //         }
    
    public function getAllCategoriesTreeUl($searchValue = null) {
                $searchValue = trim($searchValue);

                // Step 1: If no search → get all categories
                if (empty($searchValue)) {
                    $sql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 ORDER BY name ASC");
                } else {
                    // Step 2: Get matching categories
                    $searchValue = mysqli_real_escape_string($GLOBALS['dbcon'], $searchValue);
                    $matchedSql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 AND name LIKE '%$searchValue%'");

                    $matched = [];
                    while ($row = db_fetch_array($matchedSql)) {
                        $matched[] = $row;
                    }

                    // Step 3: Collect parents of matched categories
                    $allIds = [];
                    $queue = $matched;

                    while (!empty($queue)) {
                        $cat = array_pop($queue);
                        $allIds[$cat['id']] = true;

                        if ($cat['parent_id'] > 0) {
                            $parentSql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 AND id = " . intval($cat['parent_id']));
                            if ($parent = db_fetch_array($parentSql)) {
                                if (!isset($allIds[$parent['id']])) {
                                    $queue[] = $parent; // Add parent to queue
                                }
                            }
                        }
                    }

                    // Step 4: Fetch all needed categories (matches + parents)
                    $idsList = implode(',', array_keys($allIds));
                    if (empty($idsList)) {
                        $idsList = "0"; // ensures valid SQL
                    }
                    $sql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 AND id IN ($idsList) ORDER BY name ASC");
                }

                // Build array
                $all = [];
                while ($row = db_fetch_array($sql)) {
                    $all[] = $row;
                }

                // Group by parent
                $tree = [];
                foreach ($all as $row) {
                    $tree[$row['parent_id']][] = $row;
                }

                // Recursive builder
                $renderTree = function($parent_id = 0) use (&$renderTree, $tree) {
                    if (!isset($tree[$parent_id])) return '';

                    $html = '';
                    foreach ($tree[$parent_id] as $cat) {
                        if (isset($tree[$cat['id']])) {
                            // Folder with children
                            $html .= '<li class="folder">';
                            $html .= '<div class="fi parent">' . htmlspecialchars($cat['name']) . '</div>';
                            $html .= '<ul>' . $renderTree($cat['id']) . '</ul>';
                            $html .= '</li>';
                        } else {
                            // Leaf category
                            static $contentCategoryOpen = false;

                            if (!$contentCategoryOpen) {
                                $html .= '<li><div class="content-category">';
                                $contentCategoryOpen = true;
                            }

                            $html .= '<div class="fi child" onclick="return showFilterMaterial(\'' . $cat['id'] . '\', \'' . htmlspecialchars($cat['name']) . '\')">' . htmlspecialchars($cat['name']) . '</div>';

                            // Close when last sibling
                            $siblings = $tree[$parent_id];
                            if ($cat === end($siblings)) {
                                $html .= '</div></li>';
                                $contentCategoryOpen = false;
                            }
                        }
                    }
                    return $html;
                };

                // Final wrapper
                return $renderTree(0);
            }



            function getPartnerNames($partnerAccessIds) {
                 $partnerNames = [];

                 if (!empty($partnerAccessIds)) {
                   $query = db_query("SELECT shortname AS name FROM partners WHERE id IN ($partnerAccessIds)");
                       while ($row = db_fetch_array($query)) {
                             $partnerNames[] = $row['name'];
                            }
                        }

                return implode(',', $partnerNames);
            }


            
            function getKMSAuthToken() {
                // If token already in session, return it
                // if (!empty($_SESSION['auth_token'])) {
                //     return $_SESSION['auth_token'];
                // }

                // API endpoint
                $url = "https://stage.ict360.com/ictApi_v3_1/public/user-authentication-for-mind/MTc1NjM3MzM2M2OQ==";

                // POST data
                $postData = [
                    'hash'     => '11F5gldJ4B10C8D2vyz',
                    'origin'   => 'd3d3Lm1pbmRib3guY29t'
                ];

                // Setup cURL
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                // Execute request
                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    throw new Exception('Curl error: ' . curl_error($ch));
                }
                curl_close($ch);

                // Decode response
                $data = json_decode($response, true);

                // Store auth-token in session if available
                if (isset($data['data']['auth-token'])) {
                    $_SESSION['auth_token'] = $data['data']['auth-token'];
                    return $_SESSION['auth_token'];
                } else {
                    throw new Exception("Auth token not found in API response");
                }
            }



        function getSchoolHelpdeskDetails($authToken, $schoolId) {
            // API endpoint
            $url = "https://stage.ict360.com/ictApi_v3_1/public/get-school-helpdesk-queries";

            // POST data
            $postData = [
                'auth_token' => $authToken,
                'school_id'  => $schoolId
            ];

            // Setup cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            // Execute request
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new Exception('Curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            // Decode JSON response
            $data = json_decode($response, true);

            if (isset($data['success']) && strtolower($data['success']) === 'success') {
                return $data['data']; // Returns array of tickets with communications
            } else {
                throw new Exception("API Error: " . ($data['information'] ?? 'Unknown error'));
            }
        }


        function getGroupOrderIdsStr($groupName) {

                    if (!$groupName) {
                        return "";
                    }

                    // Fetch all order IDs for the group
                    $groupOrdersResult = db_query("
                        SELECT id 
                        FROM orders 
                        WHERE group_name = '" . $groupName . "'
                    ");

                    $groupOrderIds = [];
                    
                    while ($orderRow = db_fetch_array($groupOrdersResult)) {
                        $groupOrderIds[] = intval($orderRow['id']);
                    }

                    // Convert array to comma-separated string
                    return !empty($groupOrderIds) ? implode(',', $groupOrderIds) : "";

                }


       function getActiveInvoiceCountByGroup($groupId) {
                    if (!$groupId) {
                        return 0;
                    }

                    // Query to count active invoices for the group
                    $query = db_query("
                        SELECT COUNT(*) AS total_count
                        FROM tbl_mst_invoice
                        WHERE group_id = '" . $groupId . "'
                        AND is_deleted = 0
                    ");

                    $result = mysqli_fetch_assoc($query);
                    return isset($result['total_count']) ? intval($result['total_count']) : 0;
                }


                function getAisensyFailedMessages($limit = 20 ) {
                       
                        $url = "https://apis.aisensy.com/project-apis/v1/project/68abf5ca7d30730c67382ce8/campaign/audience/68e8daedd5ce0809e1cc4ec4?category=FAILED&limit=$limit";

                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        $headers = [
                            'X-AiSensy-Project-API-Pwd: a850dc5d98af7292567f1',
                            'Accept-Charset: application/json'
                        ];
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                        $response = curl_exec($ch);

                        if (curl_errno($ch)) {
                            echo "cURL Error: " . curl_error($ch);
                            curl_close($ch);
                            return null;
                        }

                        curl_close($ch);

                        $data = json_decode($response, true);

                        // If response has a "data" key, use it
                        if (isset($data['data']) && is_array($data['data'])) {
                            $records = $data['data'];
                        } else {
                            $records = $data;
                        }
                        
                       
                        // return json_encode($records, JSON_PRETTY_PRINT);

                        // Now apply the filter
                        $filtered = array_filter($data, function ($item) {
                            return isset($item['failurePayload']['reason']) &&
                                trim($item['failurePayload']['reason']) === "This message was not delivered to maintain healthy ecosystem engagement.";
                        });

                        $filtered = array_values($filtered);

                        header('Content-Type: application/json');
                        return json_encode($data, JSON_PRETTY_PRINT);
                    }


                    function getAISensyCampaignDetail($project_id) {
                                $url = "https://apis.aisensy.com/project-apis/v1/project/$project_id/campaigns";

                                // POST body
                                $postData = [
                                    "skip" => 0,
                                    "limit" => 0,
                                    "campaignType" => "ALL"
                                ];

                                $ch = curl_init($url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

                                $headers = [
                                    'X-AiSensy-Project-API-Pwd: a850dc5d98af7292567f1',
                                    'Accept-Charset: application/json',
                                    'Content-Type: application/json'
                                ];
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                $response = curl_exec($ch);

                                if (curl_errno($ch)) {
                                    echo "cURL Error: " . curl_error($ch);
                                    curl_close($ch);
                                    return [];
                                }

                                curl_close($ch);

                                $data = json_decode($response, true);

                                // Check for valid structure
                                if (!isset($data['campaigns']) || !is_array($data['campaigns'])) {
                                    return [];
                                }

                               

                                // Extract campaign IDs
                                $campaignIds = array_map(function ($campaign) {
                                    return ["id" => $campaign['id'] ?? null,
                                           "name"=>$campaign['name'] ?? null,
                                           "template"=>$campaign['message_payload']['template']['name'] ?? null
                                        ];
                                }, $data['campaigns']);

                                return $campaignIds;
                            }



                       function saveFailedNumbersAISensy($project_id, $campaign_id)
                                                        {
                                                            // Build API URL
                                                            $url = "https://apis.aisensy.com/project-apis/v1/project/$project_id/campaign/audience/$campaign_id?category=FAILED&limit=1000";

                                                            // Initialize cURL
                                                            $ch = curl_init($url);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                                                'X-AiSensy-Project-API-Pwd: a850dc5d98af7292567f1',
                                                                'Accept-Charset: application/json'
                                                            ]);

                                                            // Execute API call
                                                            $response = curl_exec($ch);

                                                            if (curl_errno($ch)) {
                                                                echo "❌ cURL Error: " . curl_error($ch) . "<br>";
                                                                curl_close($ch);
                                                                return;
                                                            }

                                                            curl_close($ch);

                                                            $data = json_decode($response, true);

                                                            if (empty($data['data'])) {
                                                                // echo "⚠️ No failed data found for campaign: $campaign_id<br>";
                                                                return;
                                                            }

                                                            $insertCount = 0;
                                                            $skipCount = 0;

                                                            // Loop through API response
                                                            foreach ($data['data'] as $item) {
                                                                $reason = $item['failurePayload']['reason'] ?? '';

                                                                // Insert only matching reason
                                                                if ($reason === 'This message was not delivered to maintain healthy ecosystem engagement.') {

                                                                    $userNumber   = addslashes($item['userNumber'] ?? '');
                                                                    $userName     = addslashes($item['userName'] ?? '');
                                                                    $failedReason = addslashes($reason);
                                                                    $requestJson  = addslashes(json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

                                                                    // 🔍 Check for existing record
                                                                    $checkQuery = "
                                                                        SELECT id FROM tbl_failed_numbers 
                                                                        WHERE user_number = '$userNumber' AND is_deleted = 0
                                                                        LIMIT 1
                                                                    ";
                                                                    $checkResult = db_query($checkQuery);

                                                                    if (mysqli_num_rows($checkResult) > 0) {
                                                                        // Duplicate found — skip
                                                                        $skipCount++;
                                                                        continue;
                                                                    }

                                                                   
                                                                    $insertQuery = "
                                                                        INSERT INTO tbl_failed_numbers 
                                                                        (campaign_id, user_number, user_name, failed_reason, request_json, created_at)
                                                                        VALUES 
                                                                        ('$campaign_id', '$userNumber', '$userName', '$failedReason', '$requestJson', NOW())
                                                                    ";

                                                                    $result = db_query($insertQuery);

                                                                    if ($result) {
                                                                        $insertCount++;
                                                                       

                                                                        if (!empty($messageResponse["status"]) && $messageResponse["status"] == true) {                                                            
                                                                         db_query($updateQ);
                                                                        }


                                                                    }

                                                                }
                                                            }

                                                            // echo "✅ $insertCount new failed numbers inserted successfully for campaign: $campaign_id<br>";
                                                            // echo "⚠️ $skipCount duplicate numbers skipped.<br>";
                                                            return True;
                                                        }


        function createAISensyCampaign($projectId, $templateName, $campaignName)
                                                                {
                                                                    $apiUrl = "https://apis.aisensy.com/project-apis/v1/project/$projectId/campaign/api";
                                                                    $apiKey = "a850dc5d98af7292567f1"; // Your AiSensy API KEY

                                                                    $payload = [
                                                                        'template_name' => $templateName,
                                                                        'campaign_name' => $campaignName
                                                                    ];

                                                                    $curl = curl_init();

                                                                    curl_setopt_array($curl, [
                                                                        CURLOPT_URL => $apiUrl,
                                                                        CURLOPT_RETURNTRANSFER => true,
                                                                        CURLOPT_CUSTOMREQUEST => "POST",
                                                                        CURLOPT_POSTFIELDS => json_encode($payload),
                                                                        CURLOPT_HTTPHEADER => [
                                                                            "Accept: application/json",
                                                                            "Content-Type: application/json",
                                                                            "X-AiSensy-Project-API-Pwd: $apiKey"
                                                                        ],
                                                                    ]);

                                                                    $response = curl_exec($curl);
                                                                    $error = curl_error($curl);

                                                                    curl_close($curl);

                                                                    // Handle errors
                                                                    if ($error) {
                                                                        return [
                                                                            'status' => false,
                                                                            'error'  => $error
                                                                        ];
                                                                    }

                                                                    return [
                                                                        'status'   => true,
                                                                        'response' => json_decode($response, true)
                                                                    ];
                                                                }


    function sendBulkFailedUsersMessage($project_id,$template,$campaign) {
           
                                                        $dateTime = date("d-m-Y H:i:s");
                                                        $campaignName = $campaign."-".$dateTime;

                                                        $result = $this->createAISensyCampaign($project_id, $template,$campaignName);
                                                    
                                                        if ($result['status']) {

                                                            $response = $result['response'];

                                                            // Get ONLY the campaign name
                                                            $campaignNameFromAPI = $response['data']['name'] ?? null;           

                                                        } else {
                                                            die("something went wrong");
                                                        }

 

                                                            // Fetch all pending numbers
                                                            $query = "
                                                                SELECT id, campaign_id, user_number, user_name 
                                                                FROM tbl_failed_numbers 
                                                                WHERE is_deleted = 0
                                                            ";
                                                            $result = db_query($query);

                                                            if (!$result || mysqli_num_rows($result) == 0) {
                                                                return "No pending users found to send messages.";
                                                            }

                                                            $sentCount = 0;
                                                            $failedCount = 0;
                                                           

                                                            while ($row = mysqli_fetch_assoc($result)) {

                                                                $id          = $row['id'];
                                                                $campaign_id = $row['campaign_id'];
                                                                $number      = trim($row['user_number']);
                                                                $name        = $row['user_name'];

                                                                
                                                                if ($number == '' || strlen($number) < 10) {
                                                                    continue;
                                                                }
                                                                // $number
                                                                $response = $this->sendAISensyMessage(
                                                                    $project_id,
                                                                    $number,#'917065846828',
                                                                    $name,
                                                                    $campaignName                                                                           
                                                                );
                                                                

                                                                if (!empty($response["status"]) && $response["status"] === true) {

                                                                    $updateQ = "
                                                                        UPDATE tbl_failed_numbers
                                                                        SET is_deleted = 1, updated_at = NOW()
                                                                        WHERE id = $id
                                                                    ";
                                                                    db_query($updateQ);

                                                                    $sentCount++;

                                                                } else {
                                                                    $failedCount++;
                                                                }
                                                            }

                                                            return "Message Sent: $sentCount | Failed: $failedCount";
                                                        }




        function sendAISensyMessage($project_id, $phone, $name, $campaignName)
                                    {
                                        $url = "https://apis.aisensy.com/project-apis/v1/project/$project_id/campaign/api/send";   

                                        $postData = [
                                            'template_params' => [],
                                            'name' => $name,
                                            'phone_number' => $phone,
                                            'media' => [],
                                            'campaign_name' => $campaignName,
                                            'source' => 'organic',
                                            'attributes' => [
                                                'country' => 'India'
                                            ],
                                            'default_country_code' => '91',
                                            'tags' => []
                                        ];

                                        $ch = curl_init($url);

                                        curl_setopt_array($ch, [
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_POST => true,
                                            CURLOPT_POSTFIELDS => json_encode($postData),
                                            CURLOPT_HTTPHEADER => [
                                                "Accept: application/json",
                                                "Content-Type: application/json",
                                                "X-AiSensy-Project-API-Pwd: a850dc5d98af7292567f1"
                                            ]
                                        ]);

                                        $response = curl_exec($ch);

                                        if (curl_errno($ch)) {
                                            $error = curl_error($ch);
                                            curl_close($ch);
                                            return ["status" => false, "error" => $error];
                                        }

                                        curl_close($ch);

                                        return ["status" => true, "response" => json_decode($response, true)];
                                    }





        // function getAisensyFailedMessages() {
        //     // API endpoint URL
        //     $url = "https://apis.aisensy.com/project-apis/v1/project/68abf5ca7d30730c67382ce8/campaign/audience/68e8daedd5ce0809e1cc4ec4?category=FAILED";

        //     // Setup cURL
        //     $ch = curl_init($url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //     // Add required headers
        //     $headers = [                
        //         'X-AiSensy-Project-API-Pwd: a850dc5d98af7292567f1',
        //         'Accept-Charset: application/json'
        //     ];
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //     // Execute cURL request
        //     $response = curl_exec($ch);

        //     // Handle cURL errors
        //     if (curl_errno($ch)) {
        //         echo "cURL Error: " . curl_error($ch);
        //         curl_close($ch);
        //         return null;
        //     }

        //     curl_close($ch);

        //     // Decode JSON response
        //     $data = json_decode($response, true);

        //     // Optional: display data for testing
        //     header('Content-Type: application/json');
        //     return json_encode($data, JSON_PRETTY_PRINT);
        // }





}
