<?php

// function end_session(){
// 	unset($_SESSION['user_id']);
// session_destroy();
// }

// function check_timeout($timeout, &$SESSION)
//     {
//         if (isset($_SESSION['LAST_ACTIVITY']))
//         {        
//             if((time() - $_SESSION['LAST_ACTIVITY']) > $timeout)
//             {
//                 end_session($SESSION);
//             }
//             $_SESSION['LAST_ACTIVITY'] = time();
//         }
//         else 
//         {
//             end_session($SESSION);
//         }
// 	}

function connect_db()
{
	global $ARR_CFGS;
	if (!isset($GLOBALS['dbcon'])) {
		$GLOBALS['dbcon'] = mysqli_connect($ARR_CFGS["db_host"], $ARR_CFGS["db_user"], $ARR_CFGS["db_pass"], $ARR_CFGS["db_name"]) or die("Could not connect to database. Please check configuration and ensure MySQL is running.");
	}
}

global $ARR_CFGS;
if (!isset($GLOBALS['dbcon'])) {
	$GLOBALS['dbcon'] = mysqli_connect($ARR_CFGS["db_host"], $ARR_CFGS["db_user"], $ARR_CFGS["db_pass"], $ARR_CFGS["db_name"]) or die("Could not connect to database. Please check configuration and ensure MySQL is running.");
}

function db_query($sql)
{
	if ($dbcon2 == '') {
		if (!isset($GLOBALS['dbcon'])) {
			connect_db();
		}
		$dbcon2 = $GLOBALS['dbcon'];
	}
	//print_r($GLOBALS['dbcon']); die;
	$result = mysqli_query($GLOBALS['dbcon'], $sql) or die(db_error($sql));
	//print_r($result); die;
	return $result;
}
function get_insert_id()
{
	$insert_id = mysqli_insert_id($GLOBALS['dbcon']);
	return $insert_id;
}
function db_fetch_array($rs)
{
	$array = mysqli_fetch_array($rs);
	return $array;
}



function db_num_array($rs)
{
	$array = mysqli_num_rows($rs);
	return $array;
}

function db_scalar($sql, $dbcon2 = null)
{
	if ($dbcon2 == '') {
		if (!isset($GLOBALS['dbcon'])) {
			connect_db();
		}
		$dbcon2 = $GLOBALS['dbcon'];
	}
	$result = db_query($sql, $dbcon2);
	if ($line = mysql_fetch_array($result)) {
		$response = $line[0];
	}
	return $response;
}

function getSingleresult($sql, $dbcon2 = null)
{
	if ($dbcon2 == '') {
		if (!isset($GLOBALS['dbcon'])) {
			connect_db();
		}
		$dbcon2 = $GLOBALS['dbcon'];
	}
	$result = db_query($sql, $dbcon2);
	if ($line = mysqli_fetch_array($result)) {
		$response = $line[0];
	}
	return $response;
}

function db_error($sql)
{
	echo "<div style='font-family: tahoma; font-size: 14px; color: #000'><br>" . mysqli_error($GLOBALS['dbcon']) . "<br>";
	print_error();
	if (LOCAL_MODE) {
		echo "<br>sql: $sql";
	}
	echo "</div>";
}
function getmicrotime()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float) $usec + (float) $sec);
}

function is_post_back()
{
	if (count($_POST) > 0) {
		return true;
	} else {
		return false;
	}
}
function sqlquery($rs = 'exe', $tablename, $arr, $update = '', $id = '', $update2 = '', $id2 = '')
{
	$sql = db_query("DESC " . tb_Prefix . "$tablename");
	$row = mysql_fetch_array($sql);
	if ($update == '')
		$makesql = "insert into ";
	else
		$makesql = "update ";
	$makesql .= tb_Prefix . "$tablename set ";

	$i = 1;
	while ($row = mysql_fetch_array($sql)) {
		if (array_key_exists($row['Field'], $arr)) {
			if ($i != 1)
				$makesql .= ", ";
			$makesql .= $row['Field'] . "='" . ms_addslashes((is_array($arr[$row['Field']])) ? implode(":", $arr[$row['Field']]) : $arr[$row['Field']]) . "'";
			$i++;
		}
	}
	if ($update)
		$makesql .= " where " . $update . "='" . $id . "'" . (($update2 && $id2) ? " and " . $update2 . "='" . $id2 . "'" : "");
	if ($rs == 'show') {
		echo $makesql;
		exit;
	} else {
		db_query($makesql);
	}
	return ($update) ? $id : mysql_insert_id();
}


function getFilename($filename)
{
	$uniq = uniqid("");
	$arr = explode('.', $filename);
	$ext = $arr[count($arr) - 1];

	$allowed = "/[^a-z0-9\\_]/i";
	$arr[0] = preg_replace($allowed, "", $arr[0]);

	$filename = $uniq . $arr[0] . "_." . $ext;

	return $filename;
}



function showmess()
{
	if ($_SESSION['sessmsg']) {

		echo $_SESSION['sessmsg'];

		$_SESSION['sessmsg'] = '';
		unset($_SESSION['sessmsg']);
	}
}

function sessset($val)
{
	$_SESSION['sessmsg'] = $val;
}


function randomPassword()
{
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function send_mail($email, $subject, $message)
{
	if ($subject && $email && $password) {



		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ICT DR Portal <noreplydrportal@arkinfo.in>' . "\r\n";


		@mail($email, $subject, $message1, $headers);


	}



}

function ms_addslashes($var)
{
	return is_array($var) ? array_map('ms_addslashes', $var) : addslashes(stripslashes(trim($var)));
}

function print_error()
{
	$debug_backtrace = debug_backtrace();
	for ($i = 1; $i < count($debug_backtrace); $i++) {
		$error = $debug_backtrace[$i];
		echo "<br>";
		echo "<div style='color:#000'>";
		echo "<span>File:</span> " . str_replace(SITE_FS_PATH, '', $error['file']) . "<br>";
		echo "<span>Line:</span> " . $error['line'] . "<br>";
		echo "<span>Function:</span> " . $error['function'] . "<br>";
		//echo "<b>Args:</b> ";
		//foreach($error['args'] as $arg) {
		//	echo "$arg <br>";
		//}
		echo "</div>";
	}
}
function redir($url, $inpage = 0)
{
	if ($inpage == 0) {
		header('location: ' . $url) or die("Cannot Send to next page");
		exit;
	} else {
		echo '
		<script type="text/javascript">
		<!--
		window.location.href="' . $url . '";
		-->
		</SCRIPT>'
		;
		exit;
	}
}

function protect_admin_page()
{

	if ($_SESSION['user_id'] == '' || $_SESSION['name'] == '') {
		header('Location:index.php');
		exit;
	}

}
function admin_protect()
{

	if ($_SESSION['user_id'] == '') {
		echo '<script type="text/javascript">window.location.href = "index.php";</script>';
		// header('Location:index.php');
		exit;
	}

}

function admin_protect2()
{
	// Start session if not already started
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	// Check if user_id is not set or empty
	if (empty($_SESSION['user_id'])) {
		header('Location: index.php');
		exit;
	}
}

function cross_login_protect($field)
{
	if ($_SESSION['user_id'] != $field) {
		redir('dashboard.php', true);
	}
}


function admin_page()
{
	if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'RM' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'OPERATIONS EXECUTIVE' || $_SESSION['user_type'] == 'RENEWAL TL' || $_SESSION['user_type'] == 'EM' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'RCLR' || $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'TEAM LEADER' || $_SESSION['role'] == 'ISS' || $_SESSION['role'] == 'DA') {

	} else {
		redir('index.php', true);
	}

}

function teamLead_page()
{
	if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'RM' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'OPERATIONS EXECUTIVE' || $_SESSION['user_type'] == 'RENEWAL TL' || $_SESSION['user_type'] == 'EM' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'TEAM LEADER' || $_SESSION['user_type'] == 'ISS SBE MNGR') {

	} else {
		redir('index.php', true);
	}

}

function iss_dashboard()
{
	if ($_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'CLR') {

	} else {
		redir('index.php', true);
	}

}

function sbe_dashboard()
{
	if ($_SESSION['user_type'] == 'ISS SBE MNGR') {

	} else {
		redir('index.php', true);
	}

}

function admin_page_rawLapsed()
{
	if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'RM' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'OPERATIONS EXECUTIVE' || $_SESSION['user_type'] == 'RENEWAL TL' || $_SESSION['user_type'] == 'EM' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'ISS SBE MNGR') {

	} else {
		redir('index.php', true);
	}

}

function partner_page()
{
	if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR' || $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'SALES MNGR') {

	} else {
		redir('dashboard.php', true);
	}
}

function business_owner_page()
{
	if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'SALES MNGR') {

	} else {
		redir('dashboard.php', true);
	}
}

function getextention($fname)
{
	$fext = explode(".", $fname);
	$ext = $fext[count($fext) - 1];
	return $ext;
}

function week_range($date)
{
	$ts = strtotime($date);
	$start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
	return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next sunday', $start)));
}
function dateDiffInDays($date1, $date2)
{
	// Calulating the difference in timestamps 
	$diff = strtotime($date2) - strtotime($date1);

	// 1 day = 24 hours 
	// 24 * 60 * 60 = 86400 seconds 
	return abs(round($diff / 86400));
}

function diffhours($startdate, $enddate)
{
	$starttimestamp = strtotime($startdate);
	$endtimestamp = strtotime($enddate);
	$difference = abs($endtimestamp - $starttimestamp) / 3600;
	return $difference;
}



	function sendMailReminder($to, $subject, $htmlBody) {
			// $to = "rahul.chugh@arkinfo.in";
		    $mail = new PHPMailer(true);
		    try {
		    	$mail->CharSet = 'UTF-8';
		        $mail->isSMTP();
		        $mail->Host       = 'smtp.office365.com';
		        $mail->SMTPAuth   = true;
		$usernameEmail = getSingleresult("SELECT email_username FROM mst_confidentials limit 1");
		$passwordEmail = getSingleresult("SELECT email_password FROM mst_confidentials limit 1");
		$mail->Username = $usernameEmail;
		$mail->Password = $passwordEmail;
		        $mail->SMTPSecure = 'tls';
		        $mail->Port       = 587;

		$mail->setFrom('drsupport@ict360.com', 'ICT DR Support');
		$mail->addReplyTo('drsupport@ict360.com', 'ICT DR Support');

		// Add single recipient
		if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
			$mail->addAddress($to);
		} else {
			return false;
		}

		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $htmlBody;
		$mail->AltBody = strip_tags($htmlBody);

		$mail->send();
		return true;
	} catch (Exception $e) {
		return false;
		// echo "Error sending to $to: {$mail->ErrorInfo}<br>";
	}
}

// function sendMailReminder($addTo, $addCc, $addBcc, $setSubject, $body){

// 		    $mail = new PHPMailer();
// 			// $mail = new PHPMailer();
// 			$mail->IsSMTP();  // set mailer to use SMTP
// 			$mail->Host = "smtp.office365.com";  // specify main and backup server
// 			$mail->SMTPAuth = true; 
// 			$mail->Port     = 587;
// 			$mail->SMTPSecure = 'tls';     // turn on SMTP authentication
// 			$mail->Username = "drsupport@ict360.com";  // SMTP username
// 			$mail->Password = "TechDR#3210"; // SMTP password Noida@123
// 			$mail->From ='drsupport@ict360.com';// 
// 			$mail->FromName = 'ICT DR Support';
// 			$mail->AddReplyTo("drsupport@ict360.com");

// 			$mail->WordWrap = 50;    
// 			$mail->IsHTML(true);       
// 			$mail->AddReplyTo("drsupport@ict360.com", "ICT DR Support");

// 			$mail->Subject = "$setSubject";

// 			$mail->AddAddress("rahul.chugh@arkinfo.in");
// 			// $mail->addCC($addCc);

// 			$mail->Body = $body;
// 			$a =  $mail->Send();
// 			echo "sent";
// 	}

function sendMail($addTo, $addCc, $addBcc, $setSubject, $body)
{
	// return true;
	$emailSendType = 0; // 0 for phpmailer and 1 for sendgrid
	// echo "<br>";echo "<br>";echo "<br>";echo "<br>to";print_r($addTo);echo "<br>cc";print_r($addCc);echo "<br>";print_r($addBcc);echo "<br>";print_r($setSubject);echo "<br>";print_r($body);	die;
	// $addBcc[] = 'ankit.aggarwal@arkinfo.in';
	// $addBcc[] = 'virendra.kumar@arkinfo.in';
	if ($emailSendType == 0) {
		$mail = new PHPMailer();
		// $mail = new PHPMailer();
		$mail->IsSMTP();  // set mailer to use SMTP
		// $mail->Host = "smtp.office365.com";  // specify main and backup server
		$mail->Host = "email-smtp.ap-south-1.amazonaws.com";
		$mail->SMTPAuth = true;
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';     // turn on SMTP authentication
		$usernameEmail = getSingleresult("SELECT email_username FROM mst_confidentials limit 1");
		$passwordEmail = getSingleresult("SELECT email_password FROM mst_confidentials limit 1");
		$mail->Username = $usernameEmail;
		$mail->Password = $passwordEmail;
		$mail->From = "support@arkinfo.in";// 
		$mail->FromName = 'DR Support';
		$replyToEmail = filter_var((string)$usernameEmail, FILTER_VALIDATE_EMAIL) ? (string)$usernameEmail : 'support@arkinfo.in';
		$mail->AddReplyTo($replyToEmail);

		$mail->WordWrap = 50;
		$mail->IsHTML(true);
		// $mail->AddReplyTo("drsupport@ict360.com", "ICT DR Support");

		$mail->Subject = "$setSubject";
		foreach ($addTo as $to) {
			if (filter_var((string)$to, FILTER_VALIDATE_EMAIL)) {
				$mail->AddAddress("$to");
			}
		}
		if ($addCc) {
			foreach ($addCc as $cc) {
				if (filter_var((string)$cc, FILTER_VALIDATE_EMAIL)) {
					$mail->AddCC("$cc");
				}
			}
		}
		if ($addBcc) {
			foreach ($addBcc as $bcc) {
				if (filter_var((string)$bcc, FILTER_VALIDATE_EMAIL)) {
					$mail->AddBCC("$bcc");
				}
			}
		}
		// $mail->AddAddress("pradeep.chahal@arkinfo.in");
		// $mail->addCC("virendra.kumar@arkinfo.in");

		$mail->Body = $body;
		$a = $mail->Send();
		// echo "<pre>";
		// print_r($mail);
		// echo "<br>";
		// print_r(error_get_last());
		// die;
		// $mail->ClearAllRecipients();

	} else if ($emailSendType == 1) {
		$email = new \SendGrid\Mail\Mail();
		$email->setFrom("support@arkinfo.in", "Support");

		$sendgrid = new \SendGrid('YOUR_SENDGRID_API_KEY');

		// $sg_key = getSingleresult("select sg_key from sendgrid_cred limit 1");
		// $sendgrid = new \SendGrid($sg_key);

		// foreach ($addTo as $to)
		// {     $email->addTo("$to");    }
		// if($addCc){
		// 	foreach ($addCc as $cc)
		// 	{		$email->addCC("$cc"); 	}
		// }
		// if($addBcc){
		// 	foreach ($addBcc as $bcc)
		// 	{		$email->addBCC("$bcc");		}
		// }
		$email->addTo("pradeep.chahal@arkinfo.in");
		// $email->addTo("bhuban.singh@arkinfo.in");
		// $email->addCc("virendra.kumar@arkinfo.in");

		$email->setSubject("$setSubject");
		$email->addContent("text/html", $body);
		try {
			$response = $sendgrid->send($email);
			echo "Status Code: " . $response->statusCode() . "<br>";
			if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
				echo "Email sent successfully.";
			} else {

				echo "Failed to send email. Status: " . $response->statusCode();
				echo "<br>Body: " . $response->body();
			}
		} catch (Exception $e) {
			echo 'Caught exception: ' . $e->getMessage();
		}
	} else if ($emailSendType == 2) {
		$data = [
			"api_key" => "api-2BFFC08034354A8686B9D64D5084C4A4",
			"to" => [
				"pradeep.chahal@arkinfo.in",
				"virendra.kumar@arkinfo.in",
				"pradeepchahal905@gmail.com"
			],
			"to_name" => [
				"Pradeep Chahal",
				"Virendra Kumar",
				"Pradeep Chahall"
			],

			"cc" => [
				"gajendra.singh@arkinfo.in"
			],
			"cc_name" => [
				"Gajendra Singh"
			],

			"bcc" => [
				"rahul.chugh@arkinfo.in"
			],
			"bcc_name" => [
				"Rahul Chugh"
			],
			"sender" => "support@arkinfo.in",
			"subject" => "Test Email via SMTP2GO API",
			"text_body" => "This is a plain text body.",
			"html_body" => "<p>This is an <strong>HTML</strong> body.</p>"
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://api.smtp2go.com/v3/email/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'Curl error: ' . curl_error($ch);
		} else {
			$result = json_decode($response, true);
			if ($result['data']['succeeded'] === 1) {
				echo "Email sent successfully!";
			} else {
				echo "Error: " . json_encode($result);
			}
		}

		curl_close($ch);
	}
}

function sendMailAttachment($addTo, $addCc, $addBcc, $setSubject, $body, $attachments)
{
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = "smtp.office365.com";
	$mail->SMTPAuth = true;
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->Username = "drsupport@ict360.com";
	$mail->Password = "@321";
	$mail->From = 'drsupport@ict360.com';
	$mail->FromName = 'ICT DR Support';
	$mail->AddReplyTo("drsupport@ict360.com");
	$mail->WordWrap = 50;
	$mail->IsHTML(true);
	$mail->AddReplyTo("drsupport@ict360.com", "ICT DR Support");
	$mail->Subject = "$setSubject";
	foreach ($addTo as $to) {
		$mail->AddAddress("$to");
	}
	if ($addCc) {
		foreach ($addCc as $cc) {
			$mail->AddCC("$cc");
		}
	}
	if ($addBcc) {
		foreach ($addBcc as $bcc) {
			$mail->AddBCC("$bcc");
		}
	}
	if ($attachments) {
		foreach ($attachments as $attachment) {
			$mail->AddAttachment("$attachment");
		}
	}
	$mail->Body = $body;
	$a = $mail->Send();
	$mail->ClearAllRecipients();
}




function getPusherCredentials($requestedKey = null)
{
	$credentials = [
		'key' => 'b2125d64edf5e1a092e2',
		'secret' => '55824a6f034b1aac03a5',
		'app_id' => '1972669',
	];

	// If any credential is missing, just log warning (optional)
	// You can throw exception here if you want hard failure

	if ($requestedKey !== null) {
		return array_key_exists($requestedKey, $credentials) ? $credentials[$requestedKey] : null;
	}

	return $credentials;
}



function getAllSubMainProduct($id = null, $isEditCase = false)
{

	$whereCondition = "";

	if ($isEditCase && $id !== null) {
		$whereCondition = "WHERE sub_p.status = 1 AND sub_p.id != " . intval($id);
	}

	$query = "SELECT 
					                  sub_p.id,
					                  sub_p.product_name,
					                  sub_p.list_price,
					                  sub_p.product_code ,
					                  sub_p.sac_code,
					                  sub_p.status,
					                  main_p.name as main_product_name
					              FROM tbl_product_opportunity sub_p
					              LEFT JOIN tbl_main_product_opportunity as main_p 					              
					              ON main_p.id = sub_p.main_product_id   
					              $whereCondition
					              ORDER BY sub_p.id DESC";

	return db_query($query);

}





function getMainProduct()
{
	$query = "SELECT * FROM tbl_main_product_opportunity Where status = 1 ORDER BY id DESC";
	return db_query($query);
}


function getAllReminderReport($usertype)
{

	if ($usertype == "ADMIN") {
		$whrCond = "";
	} else {
		$whrCond = " AND user_id =" . $_SESSION['user_id'];
	}

	$currentDateTime = date('Y-m-d H:i:s'); // Combine date + time

	$query = " SELECT ar.*, u.name, o.school_name 
				                    FROM activity_log_reminder ar 
				                    LEFT JOIN users u ON ar.user_id = u.id 
				                    LEFT JOIN orders o ON ar.lead_id = o.id 
				                    WHERE CONCAT(ar.reminder_date, ' ', ar.reminder_time) >= '$currentDateTime' 
				                       AND ar.mail_sent = 0 
				                       AND ar.user_id != 0
				                       AND ar.deleted = 0 $whrCond";

	return db_query($query);

}

function db_query_param($sql, $params = [], $types = "")
{
	if (!isset($GLOBALS['dbcon'])) {
		connect_db();
	}

	$dbcon = $GLOBALS['dbcon'];

	// Prepare the statement
	$stmt = mysqli_prepare($dbcon, $sql);
	if (!$stmt) {
		die("Prepare failed: " . mysqli_error($dbcon));
	}

	// If parameters exist, bind them
	if (!empty($params)) {
		// If no types string provided, auto-generate it (all strings by default)
		if (empty($types)) {
			$types = str_repeat("s", count($params)); // s = string
		}

		// Bind parameters dynamically
		mysqli_stmt_bind_param($stmt, $types, ...$params);
	}

	// Execute
	mysqli_stmt_execute($stmt);

	// Get result (if any)
	$result = mysqli_stmt_get_result($stmt);

	// Return result object or boolean
	return $result ?: true;
}



function admin_can_access()
{
	if ($_SESSION['user_type'] != 'ADMIN') {
		redir('index.php', true);
	}
}


function getSensyAiCredentials($key = null)
{
    $credentials = [
        'projectId' => '68abf5ca7d30730c67382ce8',
        'pswdKey'   => 'a850dc5d98af7292567f1',
    ];

    // If no key passed, return full credentials
    if ($key === null || $key === '') {
        return $credentials;
    }

    // Return value if key exists, else null
    return $credentials[$key] ?? null;
}


function db_escape($value) {
    return mysqli_real_escape_string($GLOBALS['dbcon'], $value);
}

?>