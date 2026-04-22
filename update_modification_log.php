<?php include('includes/header.php');
//include('includes/helpers.php');

if ($_SESSION['user_type'] != 'EM' && $_SESSION['user_type'] != 'REVIEWER') admin_page(); 

$sql = db_query("select * from orders where id='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_REQUEST['id']) . "'");
$row_data = db_fetch_array($sql);

if ($_SESSION['user_type'] != 'REVIEWER') {
    print_r($_POST);die;
    if ($row_data['status'] == 'Approved') {
        $ncdate = strtotime(date('Y-m-d'));
        $closeDate = strtotime($row_data['close_time']);
        if ($ncdate > $closeDate) {
            $modify_name = ($_POST['status'] == 'Approved') ? 'Qualified' : 'N/A';
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Re-log Status','Expired','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
        print_r($res);die;
    }
} 