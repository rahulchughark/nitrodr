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
    <link href="{{ asset('public/public/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/public/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/public/css/tabs.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="layout-boxed" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="140">

 <!--  BEGIN NAVBAR  -->
 <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
            
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a >
                        <img src="{{ asset('public/public/images/ict-logo.png') }}" class="navbar-logo" alt="logo">
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
                                <img alt="avatar" src="{{ asset('public/public/images/user.jpg') }}" class="rounded-circle">
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
                        <img src="{{ asset('public/public/images/ict-logo.png') }}" class="navbar-logo" alt="logo">
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
    <div class="container">
        <div class="container mt-2">

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

                <div id="tableCustomBasic" class="col-lg-6 col-6 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12 pt-2">
                                    <h4>Task Feedback</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
    <form class="row g-3">
                                    <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Task generated date :</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="Default task generated date & time">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Task due date :</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="Due date as per set matrix">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Task Owner :</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="Nikhil Panchal">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Status :</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="Not started">
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Grade :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" id="inlineFormSelectPref">
                                                <option selected="">Select</option>
                                                <option value="1">Option</option>
                                                <option value="2">Option</option>
                                                <option value="3">Option</option>
                                            </select>
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Not required tools :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" id="inlineFormSelectPref">
                                                <option selected="">Select</option>
                                                <option value="1">Option</option>
                                                <option value="2">Option</option>
                                                <option value="3">Option</option>
                                            </select>
                                        </div>
                                    </div>
									
									   <div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Tools Covered :</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" id="inlineFormSelectPref">
                                                <option selected="">Select</option>
                                                <option value="1">Option</option>
                                                <option value="2">Option</option>
                                                <option value="3">Option</option>
                                            </select>
                                        </div>
                                    </div>
									
										<div class="row mb-3">
                                        <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Session feedback : </label>
                                        <div class="col-sm-8">
                                            <select class="form-select" id="inlineFormSelectPref">
                                                <option selected="">Select</option>
                                                <option value="1">Fair</option>
                                                <option value="2">Average</option>
                                                <option value="3">Good</option>
												<option value="4">Excellent</option>
                                            </select>
                                        </div>
                                    </div>
									
									
                    

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

                           




                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

    <!--  BEGIN FOOTER  -->
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="text-muted">COPYRIGHT ©  –   2024 ICT360 , All Rights Reserved.</p>
        </div>
    </div>
    <!--  END FOOTER  -->
    
</div>
<!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->

    <!-- GLOBAL SCRIPTS -->
    <script src="{{ asset('public/public/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/public/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/public/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/public/js/mousetrap.min.js') }}"></script>
    <script src="{{ asset('public/public/js/waves.min.js') }}"></script>
    <script src="{{ asset('public/public/js/app.js') }}"></script>
    <script src="{{ asset('public/public/js/highlight.pack.js') }}"></script>
    <script src="{{ asset('public/public/js/custom.js') }}"></script>
  <!-- GLOBAL SCRIPTS -->
  <!-- BEGIN PAGE LEVEL CUSTOM SCRIPTS -->
  <script src="{{ asset('public/public/js/scrollspyNav.js') }}"></script>
 
</body>
</html>
