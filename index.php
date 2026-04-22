<?php include("includes/include.php");
include_once('helpers/logincontroller.php');

$loginObj = new logincontroller();

if (isset($_SESSION['user_id']))
    header("Location: dashboard.php");


if (isset($_POST['email'])) {
    $email = trim(mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['email']));
    $upass = trim(mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['password']));

        $user = $loginObj->Login($email,$upass);
    
    if($user === "not_exist"){
        $msg = 'Username or Password Is Wrong !';
    }else if($user === "inactive"){
        $msg = 'Your account is in-active';
    }
    
}

if (isset($_POST['reset_email'])) {

    $reset_email = trim(mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['reset_email']));

    $res = db_query("SELECT *  FROM users WHERE email='$reset_email' and status='Active'");;

    $count = mysqli_num_rows($res);
    if ($count == 1) {
        $pass_new = randomPassword();
        $to = $reset_email;
        $setSubject = 'Reset Password | Nitro Deal Registration Portal';
        $body = 'Dear User,<br/><br/>' . "\r\n";
        $body .= ' As requested, your password has been reset as <b>' . $pass_new . "</b> for login id $reset_email<br/><br/>\r\n";
        $body .= ' Thanks, <br/> Nitro Team' . "\r\n";
        $addTo[] = $to;
        // $addCc[] = "prashant.dongrikar@arkinfo.in";
                
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);


        $res = db_query("update users set password='" . md5($pass_new) . "' WHERE email='$reset_email'");

        $msg = 'Password sent to your registered email !';
    } else {
        $msg = 'This email is not associated with any account !';
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Nitro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Nitro" name="description" />
    <meta content="Nitro" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="images/favicon.png">
    <!-- Bootstrap Css -->
    <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Custom Css-->
    <link href="css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="css/colors/antigravity.css" id="theme" rel="stylesheet" type="text/css" />

</head>

<body>
    <style>

        body {
            background: #fff;
        }

        .custom-checkbox label:before, .multiselect-container.dropdown-menu li a label:before {
            margin-right: 10px;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- Log In page -->
        <div class="row">
            <div class="col-lg-8 p-0 vh-100 login-left d-flex justify-content-center">
                <div class="accountbg d-flex align-items-center">
                    <div class="account-title text-center text-white">
                        <h4 class="mt-3 text-white">Welcome To <span class="text-primary">Nitro Deal Registration</span> </h4>
                        <h1 class="text-white">Let's Get Started</h1>

                        <div class="border w-25 mx-auto border-primary"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 pr-0 pl-0 auth-body-bg d-flex align-items-center">
                <div class="card w-100 mb-0 shadow-none radius-0">
                    <div class="card-body login-box">

                        <h3 class="text-center m-0">
                            <a href="#" class="logo logo-admin"><img src="images/nitro-logo.png" height="40" alt="logo" class="my-3"></a>
                        </h3>

                        <div class="px-2 mt-2">

                            <p class="text-muted text-center">Sign in to continue to Nitro DR.</p>
                            <div style="color:#F00; text-align:center;">
                                <p><?= (!is_null($msg) ? $msg : ''); ?></p>
                            </div>
                            <form class="form-horizontal my-4" method="post" id="loginform" action="#">

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                                        </div>
                                        <input type="email" name="email" class="form-control" id="username" placeholder="Enter username" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="userpassword">Password</label>
                                    <div class="row align-items-center">
                                        <div class="input-group col pr-0">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2"><i class="fa fa-key"></i></span>
                                            </div>
                                            <input type="password" name="password" class="form-control" id="userpassword" placeholder="Enter password" required>
                                        </div>
                                        <div class="col-auto">
                                            <div class="custom-checkbox">
                                                <input type="checkbox" value="1" id="check" class="filled-in chk-col-blue">
                                                <label class="mb-0" for="check">Show</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-auto">
                                                    <div class="custom-checkbox">
                                                    </div>
                                                </div> -->
                                </div>

                                <div class="form-group row mt-4">
                                    <div class="col-sm-6">
                                        <div class="custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customControlInline">
                                            <label class="checkmark" for="customControlInline">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="javascript:void(0)" id="to-recover" class="text-muted font-13"><i class="mdi mdi-lock"></i> Forgot password?</a>
                                    </div>
                                </div>

                                <div class="form-group mb-0 row">
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>




                    </div>

                    <div class="card-body forgot-pass-box" style="display: none;">

                        <h3 class="text-center m-0">
                            <a href="#" class="logo logo-admin"><img src="images/corel.png" height="40" alt="logo" class="my-3"></a>
                        </h3>

                        <div class="px-2 mt-2">
                            <h4 class="text-muted font-size-18 mb-2 text-center">Reset Password</h4>
                            <p class="text-muted text-center">Enter your Email and instructions will be sent to you!</p>

                           

                            <form class="form-horizontal my-4" action="" method="post">

                                <div class="form-group">
                                    <label for="username">Email Address</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="far fa-envelope"></i></span>
                                        </div>
                                        <input type="email" name="reset_email" class="form-control" id="username" placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group mb-0 row">
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Reset <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="m-2 text-center bg-light p-4 text-primary">                     
                            <a href="#" class="btn btn-primary waves-effect waves-light">Sign In Here</a>                
                        </div> -->


                    </div>


                </div>
            </div>

        </div>
        <!-- End Log In page -->
    </div>
    <!-- <section id="wrapper">
        <div class="login-register" style="background-image:url(assets/images/background/login-register.jpg);">        
            <div class="login-box card">
            <div class="card-body">
            <img src="assets/images/corel.png" class="pull-right" />

                <form class="form-horizontal" method="post" id="recoverform" action="#">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                       <div class="col-xs-12">
                            <input class="form-control" type="text" name="r_email" required="" placeholder="Email"> </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button id="to-signin" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="button">Back</button>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
        
    </section> -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- JAVASCRIPT -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/simplebar.min.js"></script>
    <script src="js/waves.min.js"></script>

    <script src="js/app.js"></script>

    <script>
        $("#to-recover").click(function() {
            $(".login-box").hide();
            $(".forgot-pass-box").show();
        });

        $(document).ready(function() {
                $('#check').click(function() {
                    //alert($(this).is(':checked'));
                    $(this).is(':checked') ? $('#userpassword').attr('type', 'text') : $('#userpassword').attr('type', 'password');
                });
            });
    </script>
</body>

</html>