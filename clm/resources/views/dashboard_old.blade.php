<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>ICT 360 DR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Pacifico&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">


    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
   <link href="{{ asset('public/src/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/layouts/horizontal-light-menu/css/light/plugins.css')}}" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->

 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
 <link href="{{ asset('public/src/plugins/src/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
 <link href="{{ asset('public/src/assets/css/light/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />



    <link href="{{ asset('public/layouts/horizontal-light-menu/css/dark/plugins.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->



    <link href="{{ asset('public/src/assets/css/dark/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->   
</head>
<body class="layout-boxed enable-secondaryNav" >

 <!--  BEGIN NAVBAR  -->
 <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
            
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a >
                        <img src="{{ asset('public/images/ict-logo.png') }}" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                   
                </li>
            </ul>

            <div class="search-animated toggle-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <form class="form-inline search-full form-inline search" role="search">
                    <div class="search-bar">
                        <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x search-close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </div>
                </form>
                <!-- <span class="badge badge-secondary">Ctrl + /</span> -->
            </div>

            <ul class="navbar-item flex-row ms-lg-auto ms-0 action-area">

       

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="drodpown-title message">
                            <h6 class="d-flex justify-content-between"><span class="align-self-center">Messages</span> <span class="badge badge-primary">9 Unread</span></h6>
                        </div>
                        <div class="notification-scroll">
               
                            
                        </div>
                    </div>
                    
                </li>

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar" src="{{ asset('public/images/user.jpg') }}" class="rounded-circle">
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                              
                                <div class="media-body">
                                    <h5>Admin</h5>
                                  
                                </div>
                            </div>
                        </div>
                                
                        <div class="dropdown-item">
                            <a href="auth-boxed-lockscreen.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> <span>Change Password</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="auth-boxed-signin.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                    
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->


       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>

<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="index.html">
                        <img src="{{ asset('public/images/ict-logo.png') }}" class="navbar-logo" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                  
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu">
                <a href="#dashboard" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Leads</span>
                    </div>
              
                </a>
         
            </li>

            <li class="menu">
                <a href="#dashboard" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Report</span>
                    </div>
              
                </a>
         
            </li>

            <li class="menu">
                <a href="#dashboard" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Users</span>
                    </div>
              
                </a>
         
            </li>


        </ul>
        
    </nav>

</div>
<!--  END SIDEBAR  -->


  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

            <!--  BEGIN BREADCRUMBS  -->
            <div class="secondary-nav">
                <div class="breadcrumbs-container" data-page-heading="Analytics">
                    <header class="header navbar navbar-expand-sm">
                        <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        </a>
                        <div class="d-flex breadcrumb-content">
                            <div class="page-header">

                                <div class="page-title">
                                </div>
                
                          
                            </div>
                        </div>
                   
                    </header>
                </div>
            </div>
            <!--  END BREADCRUMBS  -->

                 <!-- <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
                      <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Tracker Task Wise</h4>
                                        </div>
                                    </div>
                                </div>
                        <div class="widget-content widget-content-area "> -->

                               <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Dashboard</h4>
                                        </div>
                                    </div>
                                </div>
                    
                   
                    
                                



                    <div class="row layout-top-spacing">

<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-six">
        <div class="widget-heading">
            <h6 class="">Statistics</h6>
            <div class="task-action">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="statistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu left" aria-labelledby="statistics" style="will-change: transform;">
                        <a class="dropdown-item" href="javascript:void(0);">View</a>
                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-chart">
            <div class="w-chart-section">
                <div class="w-detail">
                    <p class="w-title">Total Visits</p>
                    <p class="w-stats">423,964</p>
                </div>
                <div class="w-chart-render-one">
                    <div id="total-users"></div>
                </div>
            </div>

            <div class="w-chart-section">
                <div class="w-detail">
                    <p class="w-title">Paid Visits</p>
                    <p class="w-stats">7,929</p>
                </div>
                <div class="w-chart-render-one">
                    <div id="paid-visits"></div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-card-four">
        <div class="widget-content">
            <div class="w-header">
                <div class="w-info">
                    <h6 class="value">Expenses</h6>
                </div>
                <div class="task-action">
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="expenses" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>

                        <div class="dropdown-menu left" aria-labelledby="expenses" style="will-change: transform;">
                            <a class="dropdown-item" href="javascript:void(0);">This Week</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Week</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-content">

                <div class="w-info">
                    <p class="value">$ 45,141 <span>this week</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                </div>
                
            </div>

            <div class="w-progress-stats">                                            
                <div class="progress">
                    <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="">
                    <div class="w-icon">
                        <p>57%</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-card-five">
        <div class="widget-content">
            <div class="account-box">

                <div class="info-box">
                    <div class="icon">
                        <span>
                            <img src="{{ asset('public/src/assets/img/money-bag.png')}}" alt="money-bag">
                        </span>
                    </div>

                    <div class="balance-info">
                        <h6>Total Balance</h6>
                        <p>$41,741.42</p>
                    </div>
                </div>

                <div class="card-bottom-section">
                    <div><span class="badge badge-light-success">+ 13.6% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></span></div>
                    <a href="javascript:void(0);" class="">View Report</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
        <div class="widget-heading">
            <div class="">
                <h5 class="">Unique Visitors</h5>
            </div>

            <div class="task-action">
                <div class="dropdown ">
                    <a class="dropdown-toggle" href="#" role="button" id="uniqueVisitors" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu left" aria-labelledby="uniqueVisitors">
                        <a class="dropdown-item" href="javascript:void(0);">View</a>
                        <a class="dropdown-item" href="javascript:void(0);">Update</a>
                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-content">
            <div id="uniqueVisits"></div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-activity-five">

        <div class="widget-heading">
            <h5 class="">Activity Log</h5>

            <div class="task-action">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="activitylog" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu left" aria-labelledby="activitylog" style="will-change: transform;">
                        <a class="dropdown-item" href="javascript:void(0);">View All</a>
                        <a class="dropdown-item" href="javascript:void(0);">Mark as Read</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-content">

            <div class="w-shadow-top"></div>

            <div class="mt-container mx-auto">
                <div class="timeline-line">
                    
                    <div class="item-timeline timeline-new">
                        <div class="t-dot">
                            <div class="t-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></div>
                        </div>
                        <div class="t-content">
                            <div class="t-uppercontent">
                                <h5>New project created : <a href="javscript:void(0);"><span>[Cork Admin]</span></a></h5>
                            </div>
                            <p>07 May, 2022</p>
                        </div>
                    </div>

                    <div class="item-timeline timeline-new">
                        <div class="t-dot">
                            <div class="t-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></div>
                        </div>
                        <div class="t-content">
                            <div class="t-uppercontent">
                                <h5>Mail sent to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></h5>
                            </div>
                            <p>06 May, 2022</p>
                        </div>
                    </div>

                    <div class="item-timeline timeline-new">
                        <div class="t-dot">
                            <div class="t-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                        </div>
                        <div class="t-content">
                            <div class="t-uppercontent">
                                <h5>Server Logs Updated</h5>
                            </div>
                            <p>01 May, 2022</p>
                        </div>
                    </div>

                    <div class="item-timeline timeline-new">
                        <div class="t-dot">
                            <div class="t-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                        </div>
                        <div class="t-content">
                            <div class="t-uppercontent">
                                <h5>Task Completed : <a href="javscript:void(0);"><span>[Backup Files EOD]</span></a></h5>
                            </div>
                            <p>30 Apr, 2022</p>
                        </div>
                    </div>

                    <div class="item-timeline timeline-new">
                        <div class="t-dot">
                            <div class="t-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg></div>
                        </div>
                        <div class="t-content">
                            <div class="t-uppercontent">
                                <h5>Documents Submitted from <a href="javascript:void(0);">Sara</a></h5>
                                <span class=""></span>
                            </div>
                            <p>25 Apr, 2022</p>
                        </div>
                    </div>

                    <div class="item-timeline timeline-new">
                        <div class="t-dot">
                            <div class="t-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg></div>
                        </div>
                        <div class="t-content">
                            <div class="t-uppercontent">
                                <h5>Server rebooted successfully</h5>
                                <span class=""></span>
                            </div>
                            <p>10 Apr, 2022</p>
                        </div>
                    </div>                                      
                </div>                                    
            </div>

            <div class="w-shadow-bottom"></div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget-four">
        <div class="widget-heading">
            <h5 class="">Visitors by Browser</h5>
        </div>
        <div class="widget-content">
            <div class="vistorsBrowser">
                <div class="browser-list">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chrome"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line><line x1="3.95" y1="6.06" x2="8.54" y2="14"></line><line x1="10.88" y1="21.94" x2="15.46" y2="14"></line></svg>
                    </div>
                    <div class="w-browser-details">
                        <div class="w-browser-info">
                            <h6>Chrome</h6>
                            <p class="browser-count">65%</p>
                        </div>
                        <div class="w-browser-stats">
                            <div class="progress">
                                <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 65%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="browser-list">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-compass"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
                    </div>
                    <div class="w-browser-details">
                        
                        <div class="w-browser-info">
                            <h6>Safari</h6>
                            <p class="browser-count">25%</p>
                        </div>

                        <div class="w-browser-stats">
                            <div class="progress">
                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 35%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="browser-list">
                    <div class="w-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <div class="w-browser-details">
                        
                        <div class="w-browser-info">
                            <h6>Others</h6>
                            <p class="browser-count">15%</p>
                        </div>

                        <div class="w-browser-stats">
                            <div class="progress">
                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                    </div>

                </div>
                
            </div>

        </div>
    </div>
</div>

<div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="row widget-statistic">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-followers">
                <div class="widget-heading">
                    <div class="w-title">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <div class="">
                            <p class="w-value">31.6K</p>
                            <h5 class="">Followers</h5>
                        </div>
                    </div>
                </div>
                <div class="widget-content">    
                    <div class="w-chart">
                        <div id="hybrid_followers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-referral">
                <div class="widget-heading">
                    <div class="w-title">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                        </div>
                        <div class="">
                            <p class="w-value">1,900</p>
                            <h5 class="">Referral</h5>
                        </div>
                    </div>
                </div>
                <div class="widget-content">    
                    <div class="w-chart">
                        <div id="hybrid_followers1"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-engagement">
                <div class="widget-heading">
                    <div class="w-title">
                        <div class="w-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        </div>
                        <div class="">
                            <p class="w-value">18.2%</p>
                            <h5 class="">Engagement</h5>
                        </div>
                    </div>
                </div>
                <div class="widget-content">    
                    <div class="w-chart">
                        <div id="hybrid_followers3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-five">

        <div class="widget-heading">

            <a href="javascript:void(0)" class="task-info">

                <div class="usr-avatar">
                    <span>FD</span>
                </div>

                <div class="w-title">

                    <h5>Figma Design</h5>
                    <span>Design Project</span>
                    
                </div>

            </a>

            <div class="task-action">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu left" aria-labelledby="pendingTask" style="will-change: transform;">
                        <a class="dropdown-item" href="javascript:void(0);">View Project</a>
                        <a class="dropdown-item" href="javascript:void(0);">Edit Project</a>
                        <a class="dropdown-item" href="javascript:void(0);">Mark as Done</a>
                    </div>
                </div>
            </div>
            
        </div>
        
        
        <div class="widget-content">

            <p>Doloribus nisi vel suscipit modi, optio ex repudiandae voluptatibus officiis commodi.</p>

            <div class="progress-data">

                <div class="progress-info">
                    <div class="task-count"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg><p>5 Tasks</p></div>
                    <div class="progress-stats"><p>86.2%</p></div>
                </div>
                
                <div class="progress">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

            </div>

            <div class="meta-info">

                <div class="due-time">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 3 Days Left</p>
                </div>


                <div class="avatar--group">

                    <div class="avatar translateY-axis more-group">
                        <span class="avatar-title">+6</span>
                    </div>
                    <div class="avatar translateY-axis">
                        <img alt="avatar" src="{{ asset('public/src/assets/img/profile-8.jpg') }}"/>
                    </div>
                    <div class="avatar translateY-axis">
                        <img alt="avatar" src="{{ asset('public/src/assets/img/profile-12.jpg') }}"/>
                    </div>
                    <div class="avatar translateY-axis">
                        <img alt="avatar" src="{{ asset('public/src/assets/img/profile-19.jpg') }}"/>
                    </div>
                    
                </div>

            </div>
            

        </div>

    </div>

</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-card-one">
        <div class="widget-content">

            <div class="media">
                <div class="w-img">
                    <img src="{{ asset('public/src/assets/img/profile-19.jpg') }}" alt="avatar">
                </div>
                <div class="media-body">
                    <h6>Jimmy Turner</h6>
                    <p class="meta-date-time">Monday, May 18</p>
                </div>
            </div>

            <p>"Duis aute irure dolor" in reprehenderit in voluptate velit esse cillum "dolore eu fugiat" nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>

            <div class="w-action">
                <div class="card-like">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                    <span>551 Likes</span>
                </div>

                <div class="read-more">
                    <a href="javascript:void(0);">Read More <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-card-two">
        <div class="widget-content">

            <div class="media">
                <div class="w-img">
                    <img src="{{ asset('public/src/assets/img/g-8.png') }}" alt="avatar">
                </div>
                <div class="media-body">
                    <h6>Dev Summit - New York</h6>
                    <p class="meta-date-time">Bronx, NY</p>
                </div>
            </div>

            <div class="card-bottom-section">
                <h5>4 Members Going</h5>
                <div class="img-group">
                    <img src="{{ asset('public/src/assets/img/profile-19.jpg') }}" alt="avatar">
                    <img src="{{ asset('public/src/assets/img/profile-6.jpg') }}" alt="avatar">
                    <img src="{{ asset('public/src/assets/img/profile-8.jpg') }}" alt="avatar">
                    <img src="{{ asset('public/src/assets/img/profile-3.jpg') }}" alt="avatar">
                </div>
                <a href="javascript:void(0);" class="btn">View Details</a>
            </div>
        </div>
    </div>
</div>

</div>


             

                           




              


            </div>

        </div>
  

    <!--  BEGIN FOOTER  -->
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="text-muted">COPYRIGHT ©  –  2024 ICT360 CLM, All Rights Reserved.</p>
        </div>
    </div>
    <!--  END FOOTER  -->
    
</div>
<!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->
<!-- Button trigger modal -->


   
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('public/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/waves/waves.min.js') }}"></script>
    <script src="{{ asset('public/layouts/horizontal-light-menu/app.js') }}"></script>
    
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('public/src/plugins/src/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/src/assets/js/dashboard/dash_1.js') }}"></script>
   
</body>
</html>
