<?php include('includes/header.php');
business_owner_page();

include_once('helpers/DataController.php');

$kraData = new DataController();
?>
<style>
    table {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
        margin: 0;
        padding: 5px;
        border: 0px;
    }

    .table-d th {
        border: 1px solid #427dfb;
    }

    .table-d td {
        border: 1px solid #427dfb;
    }
</style>
<?php
if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-d');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-d');
}


$month = date('n');
$year =  date('Y');

$query = db_query("select * from partners where status='Active' and id=" . $_SESSION['team_id']);
$row = db_fetch_array($query);

switch ($row['category']) {
    case "Platinum":
        $sales_team = 2;
        $iss_team = 3;
        $ae_team = 1;
        $installation = 1;
        break;
    case "Gold":
        $sales_team = 2;
        $iss_team = 2;
        $ae_team = 1;
        $installation = 1;
        break;
    case "Silver":
        $sales_team = 1;
        $iss_team = 1;
        $ae_team = 1;
        break;
    case "ROI Gold":
        $sales_team = 1;
        $iss_team = 1;
        $ae_team = 1;
        break;
    case "ROI Silver":
        $sales_team = 1;
        $iss_team = 1;
        //$ae_team = 1;
        break;
    default:
}

$cat_target = db_query("select * from admin_kra where cdgs_category='" . $row['category'] . "' and year=" . $year);
$cat_arr = db_fetch_array($cat_target);

$users_data = db_query("select * from users where status='Active' and team_id='" . $_SESSION['team_id'] . "' and role not in ('BO','AE')");
$row_data = db_fetch_array($users_data);



if ($_POST['kra1']) {

    if (array_sum($_POST['kra1']) >= $cat_arr['new_dr']) {

        $count = count($_POST['kra1']);

        if ($count > 0) {
            $delete_query = deleteKRA(1, $month, $year, $_SESSION['team_id']);

            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'      => 1,
                    'kra'           => $_POST['kra1'][$i],
                    'team_id'       => $_SESSION['team_id'],
                    'user_id'       => $_POST['user_id'][$i],
                    'created_by'    => $_SESSION['user_id'],
                    'deficit_user'  => $_POST['deficit_user'][$i],
                    'month'         => $month,
                    'year'          => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");
            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
      title:"Oopss!",
      text:"Unable to save.<br>NEW DR Code Per Month (Partner Accounts) is <strong>' . $cat_arr['new_dr'] . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['new_dr'] . '</strong>.",
      type: "warning"});';
        echo '}, 100);</script>';
    }
}

if ($_POST['kra2']) {
    if (array_sum($_POST['kra2']) >= $cat_arr['sales_target']) {

        $count = count($_POST['kra2']);

        if ($count > 0) {
            $delete_query = deleteKRA(2, $month, $year, $_SESSION['team_id']);
            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'          => 2,
                    'kra'             => $_POST['kra2'][$i],
                    'team_id'    => $_SESSION['team_id'],
                    'user_id'      => $_POST['user_id'][$i],
                    'created_by'       => $_SESSION['user_id'],
                    'deficit_user'     => $_POST['deficit_user'][$i],
                    'month'       => $month,
                    'year'       => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");

            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Unable to save.<br>Sales Target Achievement is <strong>' . $cat_arr['sales_target']  . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['sales_target']  . '</strong>.",
            type: "warning"});';
        echo '}, 100);</script>';
    }
}

if ($_POST['kra3']) {
    if (array_sum($_POST['kra3']) >= $cat_arr['monthly_account']) {

        $count = count($_POST['kra3']);

        if ($count > 0) {
            $delete_query = deleteKRA(3, $month, $year, $_SESSION['team_id']);
            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'          => 3,
                    'kra'             => $_POST['kra3'][$i],
                    'team_id'    => $_SESSION['team_id'],
                    'user_id'      => $_POST['user_id'][$i],
                    'created_by'       => $_SESSION['user_id'],
                    'deficit_user'     => $_POST['deficit_user'][$i],
                    'month'       => $month,
                    'year'       => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");

            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Unable to save.<br>Monthly 40 New Account to be visited is <strong>' . $cat_arr['monthly_account']  . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['monthly_account']  . '</strong>.",
            type: "warning"});';
        echo '}, 100);</script>';

        // $error = "Sum should be greater or equal to " . $sales_team * 40;
    }
}

if ($_POST['kra4']) {
    if (array_sum($_POST['kra4']) >= $cat_arr['total_visit']) {

        $count = count($_POST['kra4']);

        if ($count > 0) {
            $delete_query = deleteKRA(4, $month, $year, $_SESSION['team_id']);
            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'          => 4,
                    'kra'             => $_POST['kra4'][$i],
                    'team_id'    => $_SESSION['team_id'],
                    'user_id'      => $_POST['user_id'][$i],
                    'created_by'       => $_SESSION['user_id'],
                    'deficit_user'     => $_POST['deficit_user'][$i],
                    'month'       => $month,
                    'year'       => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");

                //$data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by,deficit_user,month,year) VALUES (4,'" . $_POST['kra4'][$i] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'][$i] . "','" . $_SESSION['user_id'] . "','" . $_POST['deficit_user'][$i] . "','" . $month . "','" . $year . "') ");
            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Unable to save.<br>Total Account Visit to be done by Sales is <strong>' . $cat_arr['total_visit']  . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['total_visit']  . '</strong>.",
            type: "warning"});';
        echo '}, 100);</script>';

        // $error = "Sum should be greater or equal to " . $sales_team * 5 * 20;
    }
}

if ($_POST['kra5']) {

    if (array_sum($_POST['kra5']) >= $cat_arr['raw_data']) {

        $count = count($_POST['kra5']);

        if ($count > 0) {
            $delete_query = deleteKRA(5, $month, $year, $_SESSION['team_id']);
            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'          => 5,
                    'kra'             => $_POST['kra5'][$i],
                    'team_id'    => $_SESSION['team_id'],
                    'user_id'      => $_POST['user_id'][$i],
                    'created_by'       => $_SESSION['user_id'],
                    'deficit_user'     => $_POST['deficit_user'][$i],
                    'month'       => $month,
                    'year'       => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");

            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Unable to save.<br>RAW Data to be added in DR 200 Per Month is <strong>' . $cat_arr['raw_data']  . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['raw_data']  . '</strong>.",
            type: "warning"});';
        echo '}, 100);</script>';
        //$error = "Sum should be greater or equal to " . $target;
    }
}

if ($_POST['kra6']) {
    if (array_sum($_POST['kra6']) >= $cat_arr['total_log']) {

        $count = count($_POST['kra6']);

        if ($count > 0) {
            $delete_query = deleteKRA(6, $month, $year, $_SESSION['team_id']);

            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'       => 6,
                    'kra'            => $_POST['kra6'][$i],
                    'team_id'        => $_SESSION['team_id'],
                    'user_id'        => $_POST['user_id'][$i],
                    'created_by'     => $_SESSION['user_id'],
                    'deficit_user'   => $_POST['deficit_user'][$i],
                    'month'       => $month,
                    'year'       => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");

                //$data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by,deficit_user,month,year) VALUES (6,'" . $_POST['kra6'][$i] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'][$i] . "','" . $_SESSION['user_id'] . "','" . $_POST['deficit_user'][$i] . "','" . $month . "','" . $year . "') ");
            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Unable to save.<br>Total Log-call @ DR Portal by ISS is <strong>' . $cat_arr['total_log']  . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['total_log']  . '</strong>.",
            type: "warning"});';
        echo '}, 100);</script>';

        //$error = "Sum should be greater or equal to " . $iss_team * 40 * 20;
    }
}

if ($_POST['kra7']) {
    if (array_sum($_POST['kra7']) >= $cat_arr['new_account']) {

        $count = count($_POST['kra7']);

        if ($count > 0) {
            $delete_query = deleteKRA(7, $month, $year, $_SESSION['team_id']);

            for ($i = 0; $i < $count; $i++) {
                $_POST['deficit_user'][$i] = is_numeric($_POST['user_id'][$i]) ? 'No' : 'Yes';

                $user_kra = [
                    'kra_name'      => 7,
                    'kra'           => $_POST['kra7'][$i],
                    'team_id'       => $_SESSION['team_id'],
                    'user_id'       => $_POST['user_id'][$i],
                    'created_by'    => $_SESSION['user_id'],
                    'deficit_user'  => $_POST['deficit_user'][$i],
                    'month'         => $month,
                    'year'          => $year,

                ];

                $data_save = $kraData->insert($user_kra, "user_kra");

                //$data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by,deficit_user,month,year) VALUES (6,'" . $_POST['kra6'][$i] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'][$i] . "','" . $_SESSION['user_id'] . "','" . $_POST['deficit_user'][$i] . "','" . $month . "','" . $year . "') ");
            }
        }
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Unable to save.<br>Total Log-call @ DR Portal by ISS is <strong>' . $cat_arr['new_account']  . '</strong> .<br>Total should be greater or equal to <strong>' . $cat_arr['new_account']  . '</strong>.",
            type: "warning"});';
        echo '}, 100);</script>';

        //$error = "Sum should be greater or equal to " . $iss_team * 40 * 20;
    }
}

if ($data_save) {
    echo '<script>';
    echo 'setTimeout(function () { swal({html:true,
            title:"Submitted!",
            text:"KRA Submitted Successfully",
            type: "success"});';
    echo '}, 100);</script>';
}

/* mail section */

$select_kra1 = kraMailData(1, $_SESSION['team_id']);
//db_query("select * from user_kra where kra_name=1 and team_id=" . $_SESSION['team_id']);
$select_kra2 = kraMailData(2, $_SESSION['team_id']);
$select_kra3 = kraMailData(3, $_SESSION['team_id']);
$select_kra4 = kraMailData(4, $_SESSION['team_id']);
$select_kra5 = kraMailData(5, $_SESSION['team_id']);
$select_kra6 = kraMailData(6, $_SESSION['team_id']);

$setSubject = 'Your KRA (Key Result Areas)';
$manager_email = getSingleresult("select email from users where role='MNGR' and team_id=" . $_SESSION['team_id']);
$addCc[] = ($manager_email);
$addCc[] = ('maneesh.kumar@arkinfo.in');
$addCc[] = ('prashant.dongrikar@arkinfo.in');

/*
mail for sales 
*/

if ($_POST['kra2'] || $_POST['kra3'] || $_POST['kra4'] || $_POST['kra5']) {
    if (mysqli_num_rows($select_kra2) > 0 && mysqli_num_rows($select_kra3) > 0 && mysqli_num_rows($select_kra4) > 0 && mysqli_num_rows($select_kra5) > 0) {

        $sales_email = db_query("select id,email from users where status='Active' and role='SAL' and team_id=" . $_SESSION['team_id']);

        while ($user_email = db_fetch_array($sales_email)) {

            $addTo[] = ($user_email['email']);

            $sales_kra2 = selectKRA(2, $user_email['id'], $_SESSION['team_id']);
            $kra_arr2 = db_fetch_array($sales_kra2);

            $sales_kra3 = selectKRA(3, $user_email['id'], $_SESSION['team_id']);
            $kra_arr3 = db_fetch_array($sales_kra3);

            $sales_kra4 = selectKRA(4, $user_email['id'], $_SESSION['team_id']);
            $kra_arr4 = db_fetch_array($sales_kra4);

            $sales_kra5 = selectKRA(5, $user_email['id'], $_SESSION['team_id']);
            $kra_arr5 = db_fetch_array($sales_kra5);

            $body = ' <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td align="center">

            <td style="background:#fbfbfb; padding:16px 0px 10px 0;">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
           
            <td align="right"></td>
            </tr>
            </table></td>
            </tr>
            <tr>				
            <td><p>Hi,</p>
            <p>You are all set now for your working. Below is your KRA assigned.</p>
                                                       
                <table cellpadding="3" cellspacing="0" class="table-d" width="100%" style="border-collapse: collapse;">
                <thead>
                <tr bgcolor="#000000" style="background:#000000; color:#ffffff"><th colspan="3">For Sales Team</th></tr>
                <tr bgcolor="#ffff00" style="background:#ffff00;">
                <th>S.No</th>
                <th>KRA Parameters</th>
                <th width="150">Target</th>
                                       
                </tr>
                </thead>
                <tbody>
                    <tr>
                <td align="center">1</td>
                    <td align="center">Sales Target Achievement (Monthly Closures)</td>
                    <td width="150" align="center">' . $kra_arr2['kra'] . ' </td>
                </tr>	
                <tr>
                <td align="center">2</td>
                    <td align="center">Monthly new account to be visited</td>
                    <td width="150" align="center">' . $kra_arr3['kra'] . ' </td>
                </tr>	
                <tr>
                <td align="center">3</td>
                    <td align="center">Total DVR Visits to be updated in DVR</td>
                    <td width="150" align="center">' . $kra_arr4['kra'] . ' </td>
                </tr>	
                <tr>
                <td align="center">4</td>
                    <td align="center">RAW Data to be added in DR Per Month</td>
                    <td width="150" align="center">' . $kra_arr5['kra'] . ' </td>
                </tr>	
                    <tr bgcolor="#ffff00" style="background:#ffff00;">
                
                    <th align="center" colspan="2">Total</th>
                    <th width="150" align="center">' . ($kra_arr2['kra'] + $kra_arr3['kra'] + $kra_arr4['kra'] + $kra_arr5['kra']) . ' </th>
                </tr>					
                                                       
                                   
            </tbody>					

            </table></td>
            </tr>
            </tbody>
           </table>';
            // echo $body;
            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        }
    }
}

/* mail for tele-callers */

if ($_POST['kra1'] || $_POST['kra2'] || $_POST['kra5'] || $_POST['kra6']) {
    if (mysqli_num_rows($select_kra1) > 0 && mysqli_num_rows($select_kra2) > 0 && mysqli_num_rows($select_kra5) > 0 && mysqli_num_rows($select_kra6) > 0) {

        $tc_email = db_query("select id,email from users where status='Active' and role='TC' and team_id=" . $_SESSION['team_id']);

        while ($user_email = db_fetch_array($tc_email)) {

            $addTo[] = ($user_email['email']);

            $tc_kra1 = selectKRA(1, $user_email['id'], $_SESSION['team_id']);
            $kra_arr1 = db_fetch_array($tc_kra1);

            $tc_kra2 = selectKRA(2, $user_email['id'], $_SESSION['team_id']);
            $kra_arr2 = db_fetch_array($tc_kra2);

            $tc_kra5 = selectKRA(5, $user_email['id'], $_SESSION['team_id']);
            $kra_arr5 = db_fetch_array($tc_kra5);

            $tc_kra6 = selectKRA(6, $user_email['id'], $_SESSION['team_id']);
            $kra_arr6 = db_fetch_array($tc_kra6);

            $body = ' <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td align="center">

            <td style="background:#fbfbfb; padding:16px 0px 10px 0;">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
           
            <td align="right"></td>
            </tr>
            </table></td>
            </tr>
            <tr>				
            <td><p>Hi,</p>
            <p>You are all set now for your working. Below is your KRA assigned.</p>
                                                       
                <table cellpadding="3" cellspacing="0" class="table-d" width="100%" style="border-collapse: collapse;">
                <thead>
                <tr bgcolor="#000000" style="background:#000000; color:#ffffff"><th colspan="3">For ISS Team</th></tr>
                <tr bgcolor="#ffff00" style="background:#ffff00;">
                <th>S.No</th>
                <th>KRA Parameters</th>
                <th width="150">Target</th>
                                       
                </tr>
                </thead>
                <tbody>
                    <tr>
                <td align="center">1</td>
                    <td align="center">Sales Target Achievement (Monthly Closures)</td>
                    <td width="150" align="center">' . $kra_arr1['kra'] . ' </td>
                </tr>	
                <tr>
                <td align="center">2</td>
                    <td align="center">NEW DR Code Per Month (Qualified Accounts)</td>
                    <td width="150" align="center">' . $kra_arr2['kra'] . ' </td>
                </tr>	
                <tr>
                <td align="center">3</td>
                    <td align="center">RAW Data to be added in DR Per Month</td>
                    <td width="150" align="center">' . $kra_arr5['kra'] . ' </td>
                </tr>	
                <tr>
                <td align="center">4</td>
                    <td align="center">Total Log a call in new accounts</td>
                    <td width="150" align="center">' . $kra_arr6['kra'] . ' </td>
                </tr>	
                    <tr bgcolor="#ffff00" style="background:#ffff00;">
                
                    <th align="center" colspan="2">Total</th>
                    <th width="150" align="center">' . ($kra_arr1['kra'] + $kra_arr2['kra'] + $kra_arr5['kra'] + $kra_arr6['kra']) . ' </th>
                </tr>					
                                                       
                                   
            </tbody>					

            </table></td>
            </tr>
            </tbody>
           </table>';
            // echo $body;
            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        }
    }
}

?>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Manage KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">Manage KRA</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="btn-group float-right" role="group" style="margin-top:0px;">

                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search">
                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="form-control" id="date_from" name="date_from" placeholder="Date" />
                                                    <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="form-control" id="date_to" name="date_to" placeholder="Date" />
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                        </form>

                                    </div>
                                </div>
                                <!--col-lg-8-->
                            </div>

                            <div class="table-responsive">
                                <h5 class="text-blue">Your Team Structure & Target (As Per Authorization)</h5>
                                <table id="leads1" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">

                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Authorization Level</th>
                                            <th><?= $row['category'] ?></th>
                                            <th>Current Status</th>

                                        </tr>
                                    </thead>

                                    <tbody style="text-align:center;">
                                        <?php $users1 = db_query("select id,role from users where team_id='" . $_SESSION['team_id'] . "' and status='Active' ");
                                        $ids = array();

                                        while ($uid = db_fetch_array($users1)) {
                                            $ids[] = $uid['id'];
                                        }
                                        $user_ids = implode(',', $ids); ?>
                                        <tr>
                                            <td>1</td>
                                            <td>Target</td>
                                            <td><?= $row['cdgs_target'] ?></td>
                                            <td><?php $target = sales_target_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids); ?>
                                                <?= $target ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>Dedicated Sales</td>
                                            <td><?= $sales_team ?></td>
                                            <td><?= getSingleresult("select count(id) from users where team_id='" . $_SESSION['team_id'] . "' and role ='SAL' and status='Active'") ?></td>

                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Dedicated ISS</td>
                                            <td><?= $iss_team ?></td>
                                            <td><?= getSingleresult("select count(id) from users where team_id='" . $_SESSION['team_id'] . "' and role ='TC' and status='Active'") ?></td>

                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Dedicated AE </td>
                                            <td><?= $ae_team ?></td>
                                            <td><?= getSingleresult("select count(id) from users where team_id='" . $_SESSION['team_id'] . "' and role ='AE' and status='Active'") ?></td>

                                        </tr>

                                    </tbody>
                                </table>

                            </div>



                            <div class="table-responsive mt-2">


                                <!-- <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> <?= (!is_null($error) ? $error : 'KRA Added Successfully'); ?>
                                </div> -->



                                <div class=" m-t-40 mb-2 col-md-12" style="overflow-x:auto; padding:0;">
                                    <!-- <h3 class="text-blue"> </h3>-->
                                    <table id="leads" class="display admin_kra table table-hover table-striped table-bordered " cellspacing="0" border="1">
                                        <thead>

                                            <tr class="kra_header">
                                                <th style="width:50px;">S.No.</th>
                                                <th style="width:250px;">KRA</th>
                                                <th style="width:80px;">
                                                    <div style="width:80px;">Target</div>
                                                </th>
                                                <?php
                                                $actual_sal_count = getSingleresult("select count(id) from users where team_id='" . $_SESSION['team_id'] . "' and role ='SAL' and status='Active' order by role");

                                                $actual_iss_count = getSingleresult("select count(id) from users where team_id='" . $_SESSION['team_id'] . "' and role ='TC' and status='Active' order by role");

                                                $sql = manageKra($_SESSION['team_id']);

                                                while ($data = db_fetch_array($sql)) {

                                                    switch ($data['role']) {
                                                        case 'SAL':
                                                            $profile = 'Sales';
                                                            break;
                                                        case 'TC':
                                                            $profile = 'ISS Caller';
                                                            break;
                                                        case 'AE':
                                                            $profile = 'Application Engineer';
                                                            break;
                                                        case 'Installation':
                                                            $profile = 'Installation';
                                                            break;
                                                        default:
                                                            $profile = 'N/A';
                                                    } ?>


                                                    <th style="width:100px;"><?= $data['name'] ?></th>
                                                <?php }

                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) { ?>
                                                    <th style="width:100px;">Deficit<?= $i ?></th>

                                                <?php  }
                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) { ?>
                                                    <th style="width:100px;">Deficit<?= $i ?></th>

                                                <?php } ?>
                                                <th>
                                                    <div style="width:80px;">Save Data</div>
                                                </th>
                                            </tr>
                                            <tr class="kra_header">
                                                <th style="width:50px;"></th>
                                                <th style="width:250px;">
                                                    <div style="width:250px;">&nbsp;</div>
                                                </th>
                                                <th style="width:80px;"></th>
                                                <?php

                                                $sql = db_query("select id from users where team_id='" . $_SESSION['team_id'] . "' and role ='SAL' and status='Active' order by role");

                                                while ($data = db_fetch_array($sql)) {
                                                ?>
                                                    <th style="text-align:center; ">Sales</th>
                                                <?php  }

                                                $sql = db_query("select id from users where team_id='" . $_SESSION['team_id'] . "' and role ='TC' and status='Active' order by role");

                                                while ($data = db_fetch_array($sql)) { ?>

                                                    <th style="text-align:center; ">ISS Caller</th>
                                                <?php }

                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) { ?>
                                                    <th style="text-align:center;">Sales</th>
                                                <?php }
                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) { ?>
                                                    <th style="text-align:center;">ISS Caller</th>

                                                <?php } ?>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody style="text-align:center;">

                                            <tr class="kra_header">
                                                <td style="width:50px;">1</td>
                                                <td style="width:250px;">Account for LC calling with mailer validation</td>
                                                <td> <?= $cat_arr['new_dr'] ?> </td>

                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;
                                                    $user_target = userTarget_Kra(1, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>

                                                    <td>
                                                        <form action="" method="post">
                                                            <input class="form-control col-md-12" type="number" min="0" name="kra1[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['new_dr'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />
                                                            <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />
                                                    </td>

                                                <?php  }
                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) {
                                                    $user_sal_target = deficitTarget_Kra(1, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra1[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['new_dr'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }

                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) {
                                                    $user_iss_target = deficitTarget_Kra(1, 'deficit_iss_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                    
                                                ?>

                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra1[]" value="<?= ($user_iss_target ? $user_iss_target : ceil($cat_arr['new_dr'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                <input type="hidden" name="user_id[]" value="deficit_iss_<?= $i ?>" />
                                                </td>

                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>
                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td style="width:250px;">Sales Target Achievement</td>
                                                <td> <?= $cat_arr['sales_target'] ?></td>
                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;
                                                    $user_target = userTarget_Kra(2, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>

                                                    <td>
                                                        <form action="" method="post">
                                                            <input class="form-control col-md-12" type="number" min="0" name="kra2[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['sales_target'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />
                                                            <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />
                                                    </td>

                                                <?php  }
                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) {
                                                    $user_sal_target = deficitTarget_Kra(2, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra2[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['sales_target'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }

                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) {
                                                    $user_iss_target = deficitTarget_Kra(2, 'deficit_iss_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                    //getSingleresult("select kra from user_kra where kra_name='2' and user_id='deficit_iss_". $i . "' and   team_id=" . $_SESSION['team_id']);
                                                ?>

                                                    <td><input class="form-control col-md-12" type="number" min="0" name="kra2[]" value="<?= ($user_iss_target ? $user_iss_target : ceil($cat_arr['sales_target'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_iss_<?= $i ?>" />
                                                    </td>

                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>
                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                            <tr>
                                                <td>3</td>
                                                <td style="width:250px;">Monthly 40 New Account to be visited</td>
                                                <td> <?= $cat_arr['monthly_account'] ?></td>
                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;

                                                    $user_target = userTarget_Kra(3, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>

                                                    <?php if ($row_data['role'] == 'SAL') { ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input class="form-control col-md-12" type="number" min="0" name="kra3[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['monthly_account'] / $sales_team)) ?>" style="width:100px;" />
                                                                <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />
                                                        </td>
                                                    <?php } else { ?>
                                                        <td>N/A</td>
                                                    <?php }
                                                }

                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) {

                                                    $user_sal_target = deficitTarget_Kra(3, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                    ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra3[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['monthly_account'] / $sales_team)) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }
                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) { ?>
                                                    <td>N/A</td>

                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>
                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                            <tr>
                                                <td>4</td>
                                                <td style="width:250px;">Total Account Visit to be done by Sales</td>
                                                <td> <?= $cat_arr['total_visit'] ?></td>
                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;
                                                    $user_target = userTarget_Kra(4, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>
                                                    <?php if ($row_data['role'] == 'SAL') { ?>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input class="form-control col-md-12" type="number" min="0" name="kra4[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['total_visit'] / $sales_team)) ?>" style="width:100px;" />
                                                                <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />
                                                        </td>
                                                    <?php } else { ?>

                                                        <td>N/A</td>

                                                    <?php }
                                                }

                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) {
                                                    $user_sal_target = deficitTarget_Kra(4, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                    ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra4[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['total_visit'] / $sales_team)) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }
                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) { ?>

                                                    <td>N/A</td>

                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>
                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                            <tr>
                                                <td>5</td>
                                                <td style="width:250px;">Account for LC calling with profile remark and Uses confirmation</td>
                                                <td> <?= $cat_arr['raw_data'] ?>
                                                </td>
                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;
                                                    $user_target = userTarget_Kra(5, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>

                                                    <td>
                                                        <form action="" method="post">
                                                            <input class="form-control col-md-12" type="number" required min="0" name="kra5[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['raw_data'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />
                                                            <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />

                                                    </td>
                                                <?php  }
                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) {

                                                    $user_sal_target = deficitTarget_Kra(5, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra5[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['raw_data'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }
                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) {

                                                    $user_iss_target = deficitTarget_Kra(5, 'deficit_iss_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                ?>

                                                    <td><input class="form-control col-md-12" type="number" min="0" name="kra5[]" value="<?= ($user_iss_target ? $user_iss_target : ceil($cat_arr['raw_data'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_iss_<?= $i ?>" />
                                                    </td>

                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>
                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                            <tr>
                                                <td>6</td>
                                                <td style="width:250px;">Total Log-call @ DR Portal</td>
                                                <td> <?= $cat_arr['total_log'] ?> </td>
                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;
                                                    $user_target = userTarget_Kra(6, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>
                                                   
                                                   <td>
                                                        <form action="" method="post">
                                                            <input class="form-control col-md-12" type="number" required min="0" name="kra6[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['total_log'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />
                                                            <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />

                                                    </td>
                                                    <?php 
                                                }
                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) { 
                                                                                                        $user_sal_target = deficitTarget_Kra(6, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra6[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['total_log'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }
                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) {
                                                    $user_iss_target = deficitTarget_Kra(6, 'deficit_iss_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra6[]" value="<?= ($user_iss_target ? $user_iss_target : ceil($cat_arr['total_log'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_iss_<?= $i ?>" />
                                                    </td>
                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>

                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                            <tr>
                                                <td>7</td>
                                                <td style="width:250px;">New account call per day 15 by each team</td>
                                                <td> <?= $cat_arr['new_account'] ?></td>
                                                <?php
                                                $sql1 = manageKra($_SESSION['team_id']);

                                                while ($row_data = db_fetch_array($sql1)) {
                                                    $total_target = 0;
                                                    $user_target = userTarget_Kra(7, $row_data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                    $total_target += $user_target; ?>

                                                    <td>
                                                        <form action="" method="post">
                                                            <input class="form-control col-md-12" type="number" min="0" name="kra7[]" value="<?= ($user_target ? $user_target : ceil($cat_arr['new_account'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />
                                                            <input type="hidden" name="user_id[]" value="<?= $row_data['id'] ?>" />
                                                    </td>

                                                <?php  }
                                                for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) {
                                                    $user_sal_target = deficitTarget_Kra(7, 'deficit_sal_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                ?>
                                                    <td>
                                                        <input class="form-control col-md-12" type="number" min="0" name="kra7[]" value="<?= ($user_sal_target ? $user_sal_target : ceil($cat_arr['new_account'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                        <input type="hidden" name="user_id[]" value="deficit_sal_<?= $i ?>" />
                                                    </td>
                                                <?php }

                                                for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) {
                                                    $user_iss_target = deficitTarget_Kra(7, 'deficit_iss_' . $i, $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']); ?>

                                                    <td><input class="form-control col-md-12" type="number" min="0" name="kra7[]" value="<?= ($user_iss_target ? $user_iss_target : ceil($cat_arr['new_account'] / ($sales_team + $iss_team))) ?>" style="width:100px;" />

                                                    <input type="hidden" name="user_id[]" value="deficit_iss_<?= $i ?>" />
                                                    </td>

                                                <?php  }
                                                if (date('n', strtotime($_GET['date_from'])) == date('n')) { ?>
                                                    <td><input type="submit" value="Save" class="btn btn-primary btn-xs" /></td>
                                                <?php } ?>
                                                </form>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "columnDefs": [{
                            "visible": false,
                            "targets": 2
                        }],
                        "order": [
                            [2, 'desc']
                        ],
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                        ],
                        lengthMenu: [
                            [10, 25, 50, 100, 500, 1000],
                            ['10', '25', '50', '100', '500', '1000']
                        ],
                        "displayLength": 25,
                        "drawCallback": function(settings) {
                            var api = this.api();
                            var rows = api.rows({
                                page: 'current'
                            }).nodes();
                            var last = null;
                            api.column(2, {
                                page: 'current'
                            }).data().each(function(group, i) {
                                if (last !== group) {
                                    $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                    last = group;
                                }
                            });
                        }
                    });
                    // Order by the grouping
                    $('#example tbody').on('click', 'tr.group', function() {
                        var currentOrder = table.order()[0];
                        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                            table.order([2, 'desc']).draw();
                        } else {
                            table.order([2, 'asc']).draw();
                        }
                    });
                });
            });
            $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ]
            });


            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'manage_kra.php';
            }
        </script>

        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("slow");
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#leads").tableHeadFixer();

            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#leads1").tableHeadFixer();

            });
        </script>