<?php include('includes/include.php');
$_REQUEST['lead_id'] = intval($_REQUEST['lead_id']);
$_POST['cd_date'] = preg_replace("([^0-9/] | [^0-9-])", "", $_POST['cd_date']);

if (isset($_POST['cd_date'])) {
    $stage = getSingleresult("select expected_close_date from orders where id=" . $_REQUEST['lead_id'] . " limit 1");
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['lead_id'] . "','Close Date','" . $stage . "','" . $_POST['cd_date'] . "',now(),'" . $_SESSION['user_id'] . "')");

    $status = db_query("update orders set expected_close_date = '" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['cd_date']) . "' where id=" . $_REQUEST['lead_id']);

    if ($status) {
        echo 'success';
    } else {

        echo 'Error :' . mysql_info();
    }
}
