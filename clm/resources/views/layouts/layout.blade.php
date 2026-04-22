<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{asset('public/images/title-icon.png')}}" type="image/x-icon">
    <title>ICT360 CLM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset('public/layouts/horizontal-light-menu/css/light/loader.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/layouts/horizontal-light-menu/css/dark/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('public/layouts/horizontal-light-menu/loader.js')}}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Pacifico&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <link href="{{ asset('public/src/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('public/css/plugins.css') }}" rel="stylesheet" type="text/css" /> 
    
    <link href="{{ asset('public/css/tabs.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('public/css/modal.css') }}" rel="stylesheet" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('public/css/spinner.css')}}" rel="stylesheet" type="text/css" />
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

  
    <!---------------------- Add new library--------------------------------->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/src/table/datatable/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/css/light/table/datatable/dt-global_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/css/light/table/datatable/custom_dt_custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/src/tomSelect/tom-select.default.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/src/plugins/css/light/tomSelect/custom-tomSelect.css')}}">
    <!----------------------------------------------------------------------->

      <!--------------------------- date libary ----------------------->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!--------------------------- end section ----------------------->

    <link href="{{ asset('public/css/bootstrap-multiselect.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="layout-boxed" data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="140">

 <!--  BEGIN NAVBAR  -->
 <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">

            <!-- <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a> -->
            <div class="toggle-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM9 11H21V13H9V11ZM3 18H21V20H3V18Z"></path></svg>
            </div>
            
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="{{route('/dashboard')}}">
                        <img src="{{ asset('public/images/ict-logo.png') }}" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                   
                </li>
            </ul>

            <!-- <div class="search-animated toggle-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <form class="form-inline search-full form-inline search" role="search">
                    <div class="search-bar">
                        <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x search-close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </div>
                </form>
            </div> -->

            <ul class="navbar-item flex-row ms-lg-auto ms-0 action-area">

       

                <!-- <li class="nav-item dropdown notification-dropdown">
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
                    
                </li> -->

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" cla   ss="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar" src="{{ asset('public/images/user.png') }}" class="rounded-circle">
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                              
                                <div class="media-body">
                                   {{ Auth::user()->name }}
                                  
                                </div>
                            </div>
                        </div>
                                
                        <!-- <div class="dropdown-item">
                            <a href="auth-boxed-lockscreen.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> <span>Change Password</span>
                            </a>
                        </div> -->


                          <!------ add new input filed for change password ----->
<div class="dropdown-item">
    <a href="{{ route('password.change') }}">
        <!-- Lock Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             width="24" height="24" viewBox="0 0 24 24" 
             fill="none" stroke="currentColor" stroke-width="2" 
             stroke-linecap="round" stroke-linejoin="round" 
             class="feather feather-lock">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg> 
        <span>{{ __('Change Password') }}</span>
    </a>
</div>
<!------- end section -------->

                        <div class="dropdown-item">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>{{ __('Logout') }}</span>
                            </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </div>


                            
                          


                        </div>
                    </div>
                    
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    @yield('content')


        <!-- GLOBAL SCRIPTS -->
        
	
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  -->
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

     <script src="{{ asset('public/js/jquery-2.2.4.min.js') }}"></script>
     <script src="{{ asset('public/js/highlight.pack.js') }}"></script>
    <script src="{{ asset('public/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('public/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/js/mousetrap.min.js') }}"></script>
    <script src="{{ asset('public/js/waves.min.js') }}"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="{{ asset('public/js/custom.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- <script src="{{ asset('public/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/tomSelect/custom-tom-select.js') }}"></script> -->
    <script src="{{ asset('public/src/plugins/src/table/datatable/datatables.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('public/js/bootstrap.bundle-4.5.2.min.js') }}"></script>
    <script src="{{ asset('public/js/prettify.min.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-multiselect.js') }}"></script>

    <script type="text/javascript">
            $(document).ready(function() {
                window.prettyPrint() && prettyPrint();
            });
        </script>
   
   <script>
    $(document).ready(function(){
        $("#staticBackdrop").modal({
        show:false,
        backdrop:'static'
        });
    });


    $(function(){
    $('.dropdown').hover(function() {
        $(this).find('ul.dropdown-menu').addClass('show');
        // $(this).addClass('show');
    },
    function() {
        $(this).find('ul.dropdown-menu').removeClass('show');
        // $(this).removeClass('show');
    });
});



   </script>
<script>
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
    @yield('scriptFun')
    <script>
        $(document).ready(function() {
          $('#filter-box').click(function(event) {
              event.stopPropagation();
              $('#filter-container').toggle();
          });
 
          $(document).click(function(event) {
              var formContainer = $("#filter-container");
              var btnLink = $("#filter-box");
              if (formContainer.has(event.target).length === 0 && btnLink.has(event.target).length === 0) {
                  formContainer.hide();
              }
          });
      });
    </script>

<script>
    // When document is clicked
    $(document).click(function (event) {
        // If the clicked target is NOT inside the sidebar or the toggle button
        if (!$(event.target).closest('.sidebar-wrapper, .sidebarCollapse').length) {
            $(".main-container.sbar-open").addClass("sidebar-closed");
            $(".main-container").removeClass("sbar-open");
        }
    });

    // When sidebar toggle button is clicked
    $(".sidebarCollapse").click(function(event) {
        event.stopPropagation(); // Prevents the click from propagating to document
        $(".main-container").addClass("sbar-open");
        $(".main-container").removeClass("sidebar-closed");
    });

  $(".toggle-button").click(function(){
    $(".navbar-ict").addClass("active");
  });

  $(".menu-hide-toggle").click(function(){
    $(".navbar-ict").removeClass("active");
  });

  // Detect click outside .navbar-ict to remove 'active' class
  $(document).click(function(event) {
    // If the click is not inside the .navbar-ict and not on the toggle button
    if (!$(event.target).closest(".navbar-ict, .toggle-button").length) {
      $(".navbar-ict").removeClass("active");
    }
  });

  $(document).ready(function() {
    $('.dropdown-btn').on('click', function(event) {
        event.stopPropagation();
  
        // Close all open dropdowns
        $('.submenu').not($(this).parent()).removeClass('show');
  
        // Toggle the clicked dropdown
        $(this).parent().toggleClass('show');
    });
  
    // Close the dropdown if the user clicks outside of it
    $(window).on('click', function() {
        $('.submenu').removeClass('show');
    });
  });

  function gnerate_activity_model() {
 
    let ajaxUrl="";
    let additionalData = {};
    let additionalDataforTask = {};

          ajaxUrl = "{{ route('activity-tracker') }}";
        additionalData = {
            customField: "Activity Tracker",
            tabPageValue:"activity",
        };
    const requestData = {
        lead_id: 0,
    };
    $.ajax({
        url: ajaxUrl,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: requestData,
        success: function (response) {
            $("#mdlData").html(response);
            $('#staticBackdrop').modal('show');
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
}
</script>
 </body>
 </html>

