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

    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/src/table/datatable/datatables.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/css/light/table/datatable/dt-global_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/css/light/table/datatable/custom_dt_custom.css')}}">
    <link href="{{ asset('public/src/assets/css/light/components/modal.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body class="layout-boxed" >

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
          <a class="nav-link dropdown-toggle " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
            <li class="menu">
                <a href="#dashboard" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Report</span>
                    </div>
              
                </a>
         
            </li>
            <li class="nav-item dropdown menu">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" id="chk" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown menu">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown 2
          </a>
          <ul class="dropdown-menu" id="chk" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action2</a></li>
            <li><a class="dropdown-item" href="#">Another action2</a></li>
            <li><hr class="dropdown-divider">2</li>
            <li><a class="dropdown-item" href="#">Something else here2</a></li>
          </ul>
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
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <h4>Tracker - Trainer wise</h4>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-end">
                                        <div class="btn-group">
  <button type="button" class="btn btn-xs btn-light dropdown-toggle my-2" data-bs-toggle="dropdown" aria-expanded="false">
  <img src="{{ asset('public/images/mdi--filter-menu.svg') }}" alt="">
  </button>
  <div class="dropdown-menu">
    <form action="" class="filter-bg">
    <div class="mb-3 ">
  <input type="text" class="form-control" id="" placeholder="Name">
</div> 
<div class="mb-3">
    <div class="input-group">
                   
                    <input type="text" class="form-control" id="fromDate" placeholder="Select From Date">
                    
                    <input type="text" class="form-control" id="toDate" placeholder="Select To Date">
                </div>
                </div>




    </form>
</div>
</div>
                                        </div>
                                    </div>
                                </div>
                    
                   
                        <div class="row layout-spacing">
                        <div class="col-lg-12">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <table id="style-1" class="table style-1 dt-table-hover">
                                        <thead>
                                            <tr>
                                               
                                               
                                                <th class="text-center">Not started</th>
                                                <th class="text-center">In progress</th>
                                                <th class="text-center">Completed</th>
                                                <th class="text-center">Re-scheduled</th>
                                                <th class="text-center">Cancelled</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                               
                                               
                                               
                                                <td class="text-center">20</td>
                                                <td class="text-center">33</td>
                                                <td class="text-center">45</td>
                                                <td class="text-center">56</td>
                                                <td class="text-center">32</td>
                                                
                                             
                                            </tr>

                                            <tr>
                                                
                                            <td class="text-center"><span data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="shadow-none badge default-button">School</span> <span class="shadow-none badge default-button">Task</span></td>
                                            <td class="text-center"><span class="shadow-none badge default-button">School</span> <span class="shadow-none badge default-button">Task</span></td>
                                            <td class="text-center"><span class="shadow-none badge default-button">School</span> <span class="shadow-none badge default-button">Task</span></td>
                                            <td class="text-center"><span class="shadow-none badge default-button">School</span> <span class="shadow-none badge default-button">Task</span></td>
                                            <td class="text-center"><span class="shadow-none badge default-button">School</span> <span class="shadow-none badge default-button">Task</span></td>
                                            </tr>

                                        </tbody>
                                    </table>
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">School List</h5>
                                                    <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
                                                </div>
                                                <div class="modal-body">
                                                <div class="row layout-spacing">
                        <div class="col-lg-12">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <table id="style-1" class="table style-1 dt-table-hover">
                                        <thead>
                                            <tr>
                                              
                                                <th>Schools</th>
                                                <th>Not started</th>
                                          
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                               
                                           
                                                <td>Shri Ram Global School </td>
                                                <td>20</td>
                                           
                                            </tr>
                                            <tr>
                                             
                                        
                                                <td>SRM Public School</td>
                                                <td>20</td>
                                        
                                            </tr>
                                            <tr>
                                          
                                                <td>LUCKY INTERNATIONAL SCHOOL</td>
                                                <td>20</td>
                                          
                                            </tr>
                                            <tr>
                                          
                                                <td>ARAVALI INTERNATIONAL SCHOOL</td>
                                                <td>20</td>
                                            
                                            </tr>
                                            <tr>
                                             
                                            
                                                <td>JINDAL WORLD SCHOOL</td>
                                                <td>20</td>
                                          
                                            </tr>
                                            <tr>
                                            
                                          
                                                <td>Genesis Global School</td>
                                                <td>20</td>
                                           
                                            </tr>
                                            <tr>
                                              
                                         
                                                <td>THE HERITAGE SCHOOL</td>
                                                <td>20</td>
                                        
                                            </tr>
                                            <tr>
                                             
                                            
                                                <td>LEARNING PATHS SCHOOL</td>
                                                <td>20</td>
                                           
                                            </tr>
                                            <tr>
                                         
                                          
                                                <td>THE MANTHAN SCHOOL</td>
                                                <td>20</td>
                                           
                                            </tr>
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>   
                                                
                                                </div>
                                                <!-- <div class="modal-footer">
                                                    <button class="btn btn-light-dark" data-bs-dismiss="modal">Discard</button>
                                                    <button type="button" class="btn btn-primary">Save</button>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
   
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('public/src/plugins/src/global/vendors.min.js') }}"></script>
    <script src="{{ asset('public/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/waves/waves.min.js') }}"></script>
    <script src="{{ asset('public/layouts/horizontal-light-menu/app.js') }}"></script>
    
    
    <script src="{{ asset('public/src/assets/js/custom.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('public/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        // var e;
        c1 = $('#style-1').DataTable({
           
           
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 10
        });


    </script>
    <!-- END PAGE LEVEL SCRIPTS -->  
    <script>
$(document).ready(function(){
$("#staticBackdrop").modal({
show:false,
backdrop:'static'
});
});
  </script>
  <script>
$(document).ready(function() {
    $('#fromDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#toDate').datepicker('setStartDate', startDate);
    });

    $('#toDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#fromDate').datepicker('setEndDate', endDate);
    });
});
</script>
</body>
</html>
