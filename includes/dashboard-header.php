<?php include("includes/include.php"); ?>
<?php admin_protect(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Corel | DR Portal</title>
    <!-- Bootstrap Core CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
    <!--link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet"-->

    <!-- chartist CSS -->
    <link href="assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.css" rel="stylesheet">
    <link href="assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.skinModern.css" rel="stylesheet">
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!--This page css - Morris CSS -->
    <link href="assets/plugins/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
<!-- New dashboard-->
    
<!-- Bootstrap Css -->
<!-- <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" /> -->
<!-- Icons Css 
<link href="css/icons.min.css" rel="stylesheet" type="text/css" />-->
<!-- App Css
<link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />-->
<!-- Custom Css
<link href="css/custom.css" id="app-style" rel="stylesheet" type="text/css" />-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

    <link href='assets/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
    <link href='assets/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />

    <!-- <link rel="stylesheet" href="assets/plugins/bootstrap-multiselect/bootstrap-multiselect.css" type="text/css">-->
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="assets/images/corel.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="assets/images/corel.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                            <!-- dark Logo text -->
                            <img src="assets/images/logo-text.png" alt="homepage" class="dark-logo" /></span>
                        <!-- Light Logo text -->
                        <!--img src="assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span> </a-->
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">

                        <!-- This is  -->
                        <!--li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <?php if ($_SESSION['user_type'] != 'CLR') { ?><li class="nav-item ml-4 col-md-12">
                                <form action="global_search.php" method="GET">

                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="" value="<?= htmlspecialchars($_REQUEST['search'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success  form-control text-white" type="submit">Search!</button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-warning form-control text-white" onclick="javascript:window.location = window.location.href.split('?')[0];" type="button">Clear</button>
                                        </span>
                                    </div>

                                </form>
                            </li><?php } ?>
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <?php /*?><li class="nav-item dropdown mega-dropdown"> <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                            <div class="dropdown-menu scale-up-left">
                                <ul class="mega-dropdown-menu row">
                                    <li class="col-lg-3 col-xlg-2 m-b-30">
                                        <h4 class="m-b-20">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <div class="carousel-item active">
                                                    <div class="container"> <img class="d-block img-fluid" src="assets/images/big/img1.jpg" alt="First slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="assets/images/big/img2.jpg" alt="Second slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="assets/images/big/img3.jpg" alt="Third slide"></div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </li>
                                    <li class="col-lg-3 m-b-30">
                                        <h4 class="m-b-20">ACCORDION</h4>
                                        <!-- Accordian -->
                                        <div id="accordion" class="nav-accordion" role="tablist" aria-multiselectable="true">
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingOne">
                                                    <h5 class="mb-0">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                  Collapsible Group Item #1
                                                </a>
                                              </h5> </div>
                                                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high. </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingTwo">
                                                    <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                  Collapsible Group Item #2
                                                </a>
                                              </h5> </div>
                                                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingThree">
                                                    <h5 class="mb-0">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                  Collapsible Group Item #3
                                                </a>
                                              </h5> </div>
                                                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name"> </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email"> </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-4 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You can give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another fifth link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li><?php */ ?>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <?php if ($_SESSION['user_type'] == 'USR' || $_SESSION['user_type'] == 'MNGR') { ?>
                            <li class="nav-item dropdown"><a class="btn btn-primary m-t-10 waves-effect wave_effect waves-dark" href="javascript:void(0);" onclick="show_selfreview()">Self Review</a></li>
                        <?php } ?>
                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS') { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" id="lead_notify" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell count">
                                        <p></p>
                                    </i>
                                    <div class="notify">
                                        <p><?php getSingleresult("select count(id) from lead_notification where sender_type='Partner' and is_read=0") ? '<span class="heartbeat"></span>' : '' ?></p>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                                    <ul>
                                        <li>
                                            <div class="drop-title">Notifications</div>
                                        </li>

                                        <li id="notif_dropdown" class="notif_dropdown"></li>
                                    </ul>
                                </div>
                            </li>
                        <?php } else if ($_SESSION['user_type'] == 'USR') { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" id="lead_notify_usr" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell count_usr">
                                        <p></p>
                                    </i>
                                    <div class="notify"><?php getSingleresult("select count(id) from lead_notification where sender_type='Admin' and receiver_id =" . $_SESSION['user_id'] . " and is_read=0") ? '<span class="heartbit"></span>' : '' ?> </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                                    <ul>
                                        <li>
                                            <div class="drop-title">Notifications</div>
                                        </li>

                                        <li id="notif_dropdown_usr"></li>
                                    </ul>
                                </div>
                            </li>
                        <?php } ?>


                        <?php /* <li>
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><div class="message-center" style="width: auto; height: 250px;">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div><div class="slimScrollBar" style="background: rgb(220, 220, 220); width: 5px; position: absolute; top: 55px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.925px;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                                    </li>
                                    */ ?>


                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-email"></i>
                                <div class="notify"> <span class=""></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right scale-up" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 0 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->

                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $_SESSION['name'] ?> <img src="assets/images/users/profile.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><img src="assets/images/users/profile.png" alt="user"></div>
                                            <div class="u-text">
                                                <h4><?= $_SESSION['name'] ?></h4>
                                                <p class="text-muted"><?= $_SESSION['email'] ?></p><a href="#" class="btn btn-rounded btn-danger btn-sm">View Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php   $agreement=getSingleresult("select agreement from partners where id='".$_SESSION['team_id']."'"); if($_SESSION['role']=='BO' && $agreement) {
                                        ?> 
                                    <li role="separator" class="divider"></li>
                                    <li><a href="uploads/agreements/<?=$agreement?>" target="_blank"><i class="ti-clip" target="_blank"></i>View Agreement</a></li>
                                    <?php } ?>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="change_password.php"><i class="ti-lock"></i> Change Password</a></li>

                                    <li role="separator" class="divider"></li>
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Language -->
                        <!-- ============================================================== -->

                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <?php /*?><div class="user-profile" style="background: url(assets/images/background/user-info.jpg) no-repeat;">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="assets/images/users/profile.png" alt="user" /> </div>
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?=$_SESSION['name']?></a>
                        <div class="dropdown-menu animated flipInY">  
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                            <div class="dropdown-divider"></div> <a href="logout.php" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a> </div>
                    </div>
                </div><?php */ ?>
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">

                    <ul id="sidebarnav">

                        <?php

                        $expAccess = explode(',', user_access($_SESSION['user_id'], $_SESSION['role_id']));
                        //print_r($_SESSION['user_id']);
                         //print_r($_SESSION['role_id']);
                        $moduleLevel1 = get_modules_by_level();
                        //print_r($moduleLevel1);
                        foreach ($moduleLevel1 as $moduleLevel1) {
                            if (in_array($moduleLevel1['id'], $expAccess)) {
                                echo '<li> <a href="' . $moduleLevel1['url'].'"  '.(($moduleLevel1['url']=='#')?'class="has-arrow waves-effect waves-dark"':'class="tooltip-tip ajax-load" title="' . $moduleLevel1['name'] . '"').' >' . (($moduleLevel1['icon']) ? '
                            <img src="' . $moduleLevel1['icon'] . '" style="width:30px; height:30px";>' : '') . '
                            <span class="hide-menu">' . $moduleLevel1['name'] . '</span></a>';
                            if($moduleLevel1['url']=='#'){
                          echo '<ul aria-expanded="false" class="collapse">';
                            
                                $moduleLevel2 = get_modules_by_level($moduleLevel1['id']);

                                foreach ($moduleLevel2 as $moduleLevel2) {
                                    if (in_array($moduleLevel2['id'], $expAccess)) {
                                        echo '<li> <a href="' . $moduleLevel2['url'] . '" onClick="' . $moduleLevel2['is_function'] . '" class="tooltip-tip ajax-load" title="' . $moduleLevel2['name'] . '" rel="tab" aria-expanded="false">' . (($moduleLevel2['icon']) ? '
                               <img src="' . $moduleLevel2['icon'] . '" style="width:30px; height:30px";>' : '') . '
                               <span class="hide-menu">' . $moduleLevel2['name'] . '</span>
                               </a></li>';
                                    }
                                    //print_r($moduleLevel2['name']);
                                }
                                echo '</ul>';
                            }
                              echo '</li>'; 
                            }
                            
                           
                        }


                        ?>

                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS') { ?>
                            <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu">Reports </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="admin_dvr.php" aria-expanded="false"><span class="hide-menu">Manage Daily Visits</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="logcalls.php" aria-expanded="false"><span class="hide-menu">Log a Call</span></a> </li>

                                    <?php if ($_SESSION['sales_manager'] != 1) { ?> 
                                        <li> <a class="waves-effect waves-dark" href="daily_report.php" aria-expanded="false"><span class="hide-menu">Daily Report</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="weekly_report.php" aria-expanded="false"><span class="hide-menu">Weekly Report</span></a> </li>

                                        <?php if ($_SESSION['user_type'] != 'RADMIN' && $_SESSION['user_type'] != 'REVIEWER') { ?> <li> <a class="waves-effect waves-dark" href="upgrade_considated_report.php" aria-expanded="false"><span class="hide-menu">Upgrade Report</span></a> </li> <?php } ?>
                                        <li> <a class="waves-effect waves-dark" href="dvr_reports.php" aria-expanded="false"><span class="hide-menu">Data Reports</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="stage_report.php" aria-expanded="false"><span class="hide-menu">Lead Stage Report</span></a> </li>
                                        <?php if ($_SESSION['user_type'] != 'RADMIN' && $_SESSION['user_type'] != 'REVIEWER') { ?> <li> <a class="waves-effect waves-dark" href="renew_report.php" aria-expanded="false"><span class="hide-menu">Renewal Stage Report</span></a> </li> <?php } ?>
                                    <?php } ?>
                                    <?php if ($_SESSION['user_type'] != 'RADMIN' && $_SESSION['user_type'] != 'REVIEWER') { ?> <li> <a class="waves-effect waves-dark" href="user_points.php" aria-expanded="false"><span class="hide-menu">Rewards Points</span></a> </li> <?php } ?>
                                    <?php if ($_SESSION['user_type'] != 'RADMIN' && $$_SESSION['user_type'] != 'REVIEWER') { ?> <li> <a class="waves-effect waves-dark" href="upgrade_points.php" aria-expanded="false"><span class="hide-menu">Upgrade Rewards Points</span></a> </li> <?php } ?>
                                    <?php if ($_SESSION['user_type'] != 'RADMIN' && $_SESSION['user_type'] != 'REVIEWER') { ?> <li> <a class="waves-effect waves-dark" href="var_promo.php" aria-expanded="false"><span class="hide-menu">VAR Promo</span></a> </li> <?php } ?>
                                    <li> <a class="waves-effect waves-dark" href="personal_report.php" aria-expanded="false"><span class="hide-menu">Personal Report</span></a> </li>
                                    <?php if ($_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?> <li> <a class="waves-effect waves-dark" href="log_report.php" aria-expanded="false"><span class="hide-menu">Call Logs Report</span></a> </li> <?php } ?>
                                </ul>
                            </li> -->


                            <?php if ($_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>
                                <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu"> Review Reports </span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li> <a class="waves-effect waves-dark" href="review_report1.php" aria-expanded="false"><span class="hide-menu">Reviewed Account</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="review_report2.php" aria-expanded="false"><span class="hide-menu">Review Vs. Closure</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="review_report3.php" aria-expanded="false"><span class="hide-menu">Review Activity</span></a> </li>
                                    </ul>
                                </li> -->
                            <?php } ?>

                          <!--  <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span class="hide-menu">Statistics</span></a>
                                 <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="admin_target_achivement.php" aria-expanded="false"><i class="mdi mdi-target"></i><span class="hide-menu"> Target Vs Achievement</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="admin_kra.php" aria-expanded="false"><i class="mdi mdi-wallet-travel"></i><span class="hide-menu"> KRA</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="admin_ta_report.php" aria-expanded="false"><i class="mdi mdi-chart-pie"></i><span class="hide-menu"> Reports</span></a> </li>
                                </ul>
                            </li> -->
                        <?php } ?>
                        <?php if ($_SESSION['user_type'] == 'RCLR') { ?>
                            <!-- <li> <a class="waves-effect waves-dark" href="renewal_caller.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Leads</span></a> </li> -->

                        <?php } ?>
                        <?php if ($_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'CQM') { ?>
                            <?php if ($_SESSION['user_type'] == 'CQM') { ?>
                                <!-- <li> <a class="waves-effect waves-dark" href="call_quality.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Manage Call Quality</span></a> </li> -->

                            <?php } ?>
                            <!-- <li> <a class="waves-effect waves-dark" href="orders_caller.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">LC Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="my_orders_caller.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">My LC Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="review_leads_callers.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Review Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="iss_leads.php" aria-expanded="false"><i class="mdi mdi-view-list"></i><span class="hide-menu">ISS Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="add_iss_leads.php" aria-expanded="false"><i class="mdi mdi-plus"></i><span class="hide-menu">Add ISS Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="task_list.php" aria-expanded="false"><i class="mdi mdi-clipboard-check"></i><span class="hide-menu">Task List</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="lead_action_list.php" aria-expanded="false"><i class="mdi mdi-hand-pointing-right"></i><span class="hide-menu"> Action List</span></a> </li> -->

                        <?php }
                        if ($_SESSION['user_type'] == 'RCLR') { ?>
                            <!-- <li> <a class="waves-effect waves-dark" href="search_renewal_caller.php" aria-expanded="false"><i class="mdi mdi mdi-magnify"></i><span class="hide-menu">Search Renewal Leads</span></a> </li> -->
                        <?php } ?>
                        <?php if ($_SESSION['user_type'] == 'USR' || $_SESSION['user_type'] == 'MNGR') {
                            //print_r($_SESSION['role']);
                            //if ($_SESSION['role'] != 'TC') { ?>
                                <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create New </span></a>
                                    <ul aria-expanded="false" class="collapse">

                                        <li> <a class="waves-effect waves-dark" onclick="select_product()" href="javascript:void(0);" aria-expanded="false"><span class="hide-menu">Lead</span></a> </li>
                                      
                                        <?php if($_SESSION['role'] == 'SAL' || $_SESSION['role'] == 'BO') {?>
                                        <li> <a class="waves-effect waves-dark" href="javascript:void(0);" onclick="select_product_dvr()" aria-expanded="false"><span class="hide-menu">DVR</span></a> </li>
                                        <?php } ?>

                                    </ul>
                                </li> -->
                            <?php //} ?>

                          <!--  <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu">Reports</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="personalReport_partner.php" aria-expanded="false"><i></i><span class="hide-menu"> Personal Report</span></a> </li>
                                </ul>
                            </li>

                             <li class="nav-small-cap">Lead Management</li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Manage Leads</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="orders.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu"> Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="search_partner_lead.php" aria-expanded="false"><i class="mdi mdi-account-search"></i><span class="hide-menu"> Search Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="review_partner_lead.php" aria-expanded="false"><i class="mdi mdi-eye-off"></i><span class="hide-menu"> Review Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="task_list.php" aria-expanded="false"><i class="mdi mdi-clipboard-check"></i><span class="hide-menu">Task List</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="raw_leads.php" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu">Raw Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="lapsed_partner_leads.php" aria-expanded="false"><i class="mdi mdi-history"></i><span class="hide-menu">Lapsed Leads</span></a> </li> -->

                                    <?php if ($_SESSION['user_type'] == 'MNGR') { ?>
                                        <!-- <li> <a class="waves-effect waves-dark" href="lead_action_list.php" aria-expanded="false"><i class="mdi mdi-hand-pointing-right"></i><span class="hide-menu">Action List</span></a> </li>-->
                                    <?php } ?>
                            <!--     </ul>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="update_leads_partner.php" aria-expanded="false"><i class="mdi mdi-arrow-up"></i><span class="hide-menu">Upgrade Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="renewal_leads_partner.php" aria-expanded="false"><i class="mdi mdi-refresh"></i><span class="hide-menu">Renewal Leads</span></a> </li>
                            <li class="nav-small-cap">Daily Visits</li>
                            <li> <a class="waves-effect waves-dark" href="dvr.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">View Daily Visits</span></a> </li> -->
                            <?php if ($_SESSION['user_type'] == 'MNGR') { ?>
                                <!-- <li> <a class="waves-effect waves-dark" href="dv_report.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Daily Visits Report</span></a> </li>
                                <li class="nav-small-cap">Reports</li>
                                <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span class="hide-menu">Statistics</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li> <a class="waves-effect waves-dark" href="target_achivement.php" aria-expanded="false"><i class="mdi mdi-target"></i><span class="hide-menu"> Target Vs Achievement</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="kra.php" aria-expanded="false"><i class="mdi mdi-wallet-travel"></i><span class="hide-menu"> KRA</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="ta_report.php" aria-expanded="false"><i class="mdi mdi-chart-pie"></i><span class="hide-menu"> Reports</span></a> </li>
                                    </ul>
                                </li> -->
                            <?php } ?>

                        <?php } ?>
                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS') { ?>
                            <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Leads </span></a>
                                <ul aria-expanded="false" class="collapse">

                                    <li> <a class="waves-effect waves-dark" href="manage_orders.php" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu"> Manage Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="search_orders.php" aria-expanded="false"><i class="mdi  mdi-account-search"></i><span class="hide-menu"> Search Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="review_leads.php" aria-expanded="false"><i class="mdi mdi mdi-eye-off"></i><span class="hide-menu"> Review Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="task_list.php" aria-expanded="false"><i class="mdi  mdi-clipboard-check"></i><span class="hide-menu"> Task List</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="lead_action_list.php" aria-expanded="false"><i class="mdi mdi-hand-pointing-right"></i><span class="hide-menu"> Action List</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="raw_leads.php" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu">Raw Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="lapsed_leads.php" aria-expanded="false"><i class="mdi mdi-history"></i><span class="hide-menu">Lapsed Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="mass_leads_alignment.php" aria-expanded="false"><i class="mdi mdi-history"></i><span class="hide-menu">Mass Lead Alignment</span></a> </li> 
                                </ul>
                            </li>-->

                            <?php if ($_SESSION['user_type'] != 'RADMIN' && $_SESSION['user_type'] != 'REVIEWER') {
                            ?> 
                            <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Upgrade Leads </span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li> <a class="waves-effect waves-dark" href="upgrade_lead_admin.php" aria-expanded="false"><span class="hide-menu">Upgrade Leads</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="search_upgrade_lead.php" aria-expanded="false"><span class="hide-menu">Search Upgrade Leads</span></a> </li>
                                        <?php if ($_SESSION['sales_manager'] != 1) { ?><li> <a class="waves-effect waves-dark" href="assign_ugrade_leads.php" aria-expanded="false"><span class="hide-menu">Align Multiple Leads</span></a> </li>
                                        <?php } ?>
                                    </ul>
                                </li> 

                                <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Renewal Leads </span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li> <a class="waves-effect waves-dark" href="renewal_leads_admin.php" aria-expanded="false"><span class="hide-menu">Renewal Leads</span></a> </li>
                                        <li> <a class="waves-effect waves-dark" href="search_renewal_lead.php" aria-expanded="false"><span class="hide-menu">Search Renewal Leads</span></a> </li>
                                        <?php if ($_SESSION['sales_manager'] != 1) { ?><li> <a class="waves-effect waves-dark" href="assign_renewal_leads.php" aria-expanded="false"><span class="hide-menu">Align Multiple Leads</span></a> </li>
                                        <?php } ?>
                                    </ul>
                                </li>-->


                        <?php }
                        } ?>
                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS') { ?>
                            <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Partners </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="manage_partners.php" aria-expanded="false"><span class="">Manage Partner</span></a>

                                    </li>
                                    <li> <a class="waves-effect waves-dark" href="manage_users.php" aria-expanded="false"><span class="hide-menu">Manage Users </span></a>
                                    </li>

                                    <li> <a class="waves-effect waves-dark" href="whatsnew_list.php" aria-expanded="false"><span class="hide-menu">Whats new </span></a>
                                    </li>

                                    <li> <a class="waves-effect waves-dark" href="manage_campaign.php" aria-expanded="false"><span class="hide-menu">Create Campaign </span></a>
                                    </li>
                                </ul>
                            </li> -->
                             <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS') { ?>
                            <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Modules </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="manage_modules.php" aria-expanded="false"><span class="">Manage Modules</span></a>
                                </ul>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="manage_role_access.php" aria-expanded="false"><span class="">Role Access</span></a>
                                </ul>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="manage_user_access.php" aria-expanded="false"><span class="">User Access</span></a>
                                </ul>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Products </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li> <a class="waves-effect waves-dark" href="manage_products.php" aria-expanded="false"><span class="">Manage Products</span></a>
                                </ul>
                            </li> -->

                        <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'RM') { ?>
                            <!-- <li> <a class="waves-effect waves-dark" href="renewal_leads_admin.php" aria-expanded="false"><i class="mdi mdi mdi-recycle"></i><span class="hide-menu">Renewal Leads</span></a> </li>
                            <li> <a class="waves-effect waves-dark" href="search_renewal_lead.php" aria-expanded="false"><i class="mdi mdi mdi-magnify"></i><span class="hide-menu">Search Renewal Leads</span></a> </li> -->

                        <?php } ?>
                        <?php if ($_SESSION['user_type'] == 'EM') { ?>
                            <!-- <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Education Leads </span></a>
                                <ul aria-expanded="false" class="collapse">

                                    <li> <a class="waves-effect waves-dark" href="education_leads_admin.php" aria-expanded="false"><i class="mdi mdi mdi-library"></i><span class="hide-menu">Education Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="search_education_lead.php" aria-expanded="false"><i class="mdi mdi mdi-magnify"></i><span class="hide-menu">Search Education Leads</span></a> </li>
                                </ul>
                            </li>

                            <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu">Renewal Leads </span></a>
                                <ul aria-expanded="false" class="collapse">

                                    <li> <a class="waves-effect waves-dark" href="renewal_leads.php" aria-expanded="false"><i class="mdi mdi mdi-library"></i><span class="hide-menu">Renewal Leads</span></a> </li>
                                    <li> <a class="waves-effect waves-dark" href="search_renew_leads.php" aria-expanded="false"><i class="mdi mdi mdi-magnify"></i><span class="hide-menu">Search Renewal Leads</span></a> </li>
                                </ul>
                            </li> -->
                        <?php } ?>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="change_password.php" class="link" id="change_password" data-toggle="tooltip" title="Change Password"><i class="ti-lock"></i></a>

                <!-- item--><a href="logout.php" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
            <!-- End Bottom points-->
        </aside>
        <div id="licence_type" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Select License Type</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input name="license_type_radio" value="add_leads.php?license_type=Commercial" type="radio" required id="license_type_radio_21" class="filled-in radio-col-red">
                        <label for="license_type_radio_21">Commercial</label>
                        <input name="license_type_radio" value="add_leads.php?license_type=Upgrade" type="radio" required id="license_type_radio_22" class="filled-in radio-col-red">
                        <label for="license_type_radio_22">Upgrade</label>
                        <input name="license_type_radio" value="add_leads.php?license_type=Education" type="radio" required id="license_type_radio_23" class="filled-in radio-col-red">
                        <label for="license_type_radio_23">Education</label>
                        <input name="license_type_radio" value="add_renewal_lead.php" type="radio" required id="license_type_radio_24" class="filled-in radio-col-red">
                        <label for="license_type_radio_24">Renewal</label>
                    </div>


                </div>

            </div>

        </div>
        <?php if ($_SESSION['user_type']!= 'ADMIN') { ?>
        <div id="product_data" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Select Product</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="product_data">
                    <div class="modal-body">
                        <div class="col-md-12">
                           <label for="product" class="control-label">Product</label>
                        </div>
                        <div class="col-md-12">
                            <select name="product" class="form-control" id="product">
                                <option value="">---Select---</option>
                                <?php $res_product = selectProductPartner($_SESSION['team_id']);
                                while ($row = db_fetch_array($res_product)) { ?>
                                    <option value=<?= $row['id']; ?>><?= $row['product_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="productType">

                        </div>

                    </div>
                    </form>
                    <div class="modal-footer justify-content-center" style="display: none;">
                    <button type="button" name="submit" id="submit_btn" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </div>

        </div>

        <div id="product_dvr" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Select Product</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="product_dvr">
                    <div class="modal-body">
                        <div class="col-md-12">
                              <label for="productDVR" class="control-label">Product</label>
                        </div>
                        <div class="col-md-12">
                            <select name="product" class="form-control" id="productDVR">
                                <option value="">---Select---</option>
                                <?php $res_product = selectProductPartner($_SESSION['team_id']);
                                while ($row = db_fetch_array($res_product)) { ?>
                                    <option value=<?= $row['id']; ?>><?= $row['product_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="productDVRType">

                        </div>

                    </div>
                    </form>
                    <div class="modal-footer justify-content-center" style="display: none;">
                    <button type="button" name="submit" id="submit_dvr" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </div>

        </div>

        <?php } ?>
        
        <div id="selfReview" class="modal modal_review" role="dialog" data-backdrop="static" data-keyboard="false">


        </div>

        <!--button class="" onclick="topFunction()" id="myBtn" title="Go to top"><i class="ti-angle-double-up"></i></button-->
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <script src="assets/plugins/jquery/jquery.min.js"></script>
        <script>
            //for admin
            $(document).ready(function() {
                $('#lead_notify').on('click', function() {
                    $('.count').html('');
                    load_unseen_notification('yes');
                });

                function load_unseen_notification(view = '') {
                    $.ajax({
                        url: "notify_lead.php",
                        method: "POST",
                        data: {
                            view: view
                        },
                        dataType: "json",
                        success: function(data) {
                            $('#notif_dropdown').html(data.notification);
                            if (data.unseen_notification > 0) {
                                $('.count').html(data.unseen_notification);
                            }
                        }
                    });
                }
                load_unseen_notification();
            });

            //for partner
            $(document).ready(function() {
                $('#lead_notify_usr').on('click', function() {
                    $('.count_usr').html('');
                    load_notification_usr('yes');
                });

                function load_notification_usr(view = '') {
                    $.ajax({
                        url: "notifyLead_partner.php",
                        method: "POST",
                        data: {
                            view: view
                        },
                        dataType: "json",
                        success: function(data) {
                            $('#notif_dropdown_usr').html(data.notification);
                            if (data.unseen_notification > 0) {
                                $('.count_usr').html(data.unseen_notification);
                            }
                        }
                    });
                }
                load_notification_usr();
            });

            function updateNotificationPartner(id, user_id, type_id) {
                //alert(id);
                $.ajax({
                    type: 'POST',
                    url: 'notifyLead_partner.php',
                    data: {
                        id: id,
                        user_id: user_id,
                        type_id: type_id
                    },
                    success: function(data) {
                        window.location.href = 'partner_view.php?id=' + type_id
                    },
                });
            }
        </script>