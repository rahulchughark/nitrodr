<!--  BEGIN SIDEBAR  -->
<!-- <div class="sidebar-wrapper sidebar-theme">

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
            {{-- <li class="menu">
                <a href="#dashboard" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Leads</span>
                    </div>
              
                </a>
         
            </li> --}}


            <li class="menu {{ Route::is('/task') ? 'active' : '' }}">
                <a href="{{route('/task')}}"  aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4H5V20H19V4ZM3 2.9918C3 2.44405 3.44749 2 3.9985 2H19.9997C20.5519 2 20.9996 2.44772 20.9997 3L21 20.9925C21 21.5489 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918ZM11.2929 13.1213L15.5355 8.87868L16.9497 10.2929L11.2929 15.9497L7.40381 12.0607L8.81802 10.6464L11.2929 13.1213Z"></path></svg>
                        <span>Task</span>
                    </div>
                </a>
            </li>

            <li class="nav-item dropdown menu {{ Route::is('report/tracker_task_wise') || Route::is('report/trainer') || Route::is('report/cumulative') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                    <div class="">                    
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"></path></svg>
                 <span> Reports</span><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M7.41 8.58L12 13.17l4.59-4.59L18 10l-6 6l-6-6z"/></svg>
                    </div>
                </a>
                <ul class="dropdown-menu" id="chk" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item {{ Route::is('report/tracker_task_wise') ? 'active' : '' }}" href="{{route('report/tracker_task_wise')}}">Tracker – Task wise</a></li>
                  <li><a class="dropdown-item {{ Route::is('report/trainer') ? 'active' : '' }}" href="{{route('report/trainer')}}">Tracker - Trainer wise</a></li>
                  <li><a class="dropdown-item {{ Route::is('report/cumulative') ? 'active' : '' }}" href="{{route('report/cumulative')}}">Tracker - Cumulative</a></li>
                </ul>
              </li>

            {{-- <li class="menu">
                <a href="#dashboard" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Users</span>
                    </div>
              
                </a>
         
            </li> --}}

           

            {{-- <li class="nav-item dropdown menu">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                 <span>Approval And Approved Reports</span>
                    </div>
                </a>
                <ul class="dropdown-menu" id="chk" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="{{route('/pending',['type'=>'reports'])}}">Task for Approval</a></li>
                  <li><a class="dropdown-item" href="{{route('/approved',['type'=>'reports'])}}">Approved Task Reports</a></li>
                </ul>
              </li> --}}
               @if (auth()->user()->user_type==="ADMIN")
               <li class="menu {{ Route::is('/task-for-approval') ? 'active' : '' }}">
                <a href="{{route('/task-for-approval')}}"  aria-expanded="false" class="dropdown-toggle">
                    <div class="">                        
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 20V22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918C3 2.44405 3.44749 2 3.9985 2H16L20.9998 7V14H19V8H15V4H5V20H12ZM14.4646 19.4647L18.0001 23.0002L22.9498 18.0505L21.5356 16.6362L18.0001 20.1718L15.8788 18.0505L14.4646 19.4647Z"></path></svg>
                        <span>Task For Approval</span>
                    </div>
              
                </a>
            </li>
            
            <li class="menu {{ Route::is('users-list') ? 'active' : '' }}">
                <a href="{{route('users-list')}}"  aria-expanded="false" class="dropdown-toggle">
                    <div class="">                        
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H18C18 18.6863 15.3137 16 12 16C8.68629 16 6 18.6863 6 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11Z"></path></svg>
                        <span>Users</span>
                    </div>
              
                </a>
         
            </li>
            @endif


        </ul>
        
    </nav>

</div> -->
<nav class="navbar-ict">
    <div class="container-fluid">
        <div class="header-inner">
            <div class="menu-hide-toggle d-lg-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"></path></svg>
            </div>
            <ul>
                <li class="{{ Route::is('/task') ? 'active' : '' }}">
                    <a href="{{route('/task')}}" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4H5V20H19V4ZM3 2.9918C3 2.44405 3.44749 2 3.9985 2H19.9997C20.5519 2 20.9996 2.44772 20.9997 3L21 20.9925C21 21.5489 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918ZM11.2929 13.1213L15.5355 8.87868L16.9497 10.2929L11.2929 15.9497L7.40381 12.0607L8.81802 10.6464L11.2929 13.1213Z"></path></svg>
                        <span>Task</span>
                    </a>
                </li>

                <li class="{{ Route::is('/renewal-task') ? 'active' : '' }}">
                    <a href="{{route('/renewal-task')}}" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4H5V20H19V4ZM3 2.9918C3 2.44405 3.44749 2 3.9985 2H19.9997C20.5519 2 20.9996 2.44772 20.9997 3L21 20.9925C21 21.5489 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918ZM11.2929 13.1213L15.5355 8.87868L16.9497 10.2929L11.2929 15.9497L7.40381 12.0607L8.81802 10.6464L11.2929 13.1213Z"></path></svg>
                        <span>Renewal Task</span>
                    </a>
                </li>


           <!-- add new tab for school list-->
           <li class="{{ Route::is('reports/all-assign-task-schools') ? 'active' : '' }}">
            <a href="{{route('reports/all-assign-task-schools')}}" >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4H5V20H19V4ZM3 2.9918C3 2.44405 3.44749 2 3.9985 2H19.9997C20.5519 2 20.9996 2.44772 20.9997 3L21 20.9925C21 21.5489 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918ZM11.2929 13.1213L15.5355 8.87868L16.9497 10.2929L11.2929 15.9497L7.40381 12.0607L8.81802 10.6464L11.2929 13.1213Z"></path></svg>
                <span>Schools</span>
            </a>
        </li>
           <!-------- end section --------->     
                <li class="{{ Route::is('report/tracker_task_wise') || Route::is('report/trainer') || Route::is('report/cumulative') ? 'active' : '' }}">
                <a class="dropdown-btn" href="javascript:void(0)">                  
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"></path></svg>
                    <span> Reports</span>                    
                </a>
                <ul class="submenu">
                  <li><a class="{{ Route::is('report/tracker_task_wise') ? 'active' : '' }}" href="{{route('report/tracker_task_wise')}}">Tracker – Task wise</a></li>
                  <li><a class="{{ Route::is('report/trainer') ? 'active' : '' }}" href="{{route('report/trainer')}}">Tracker - Trainer wise</a></li>
                  <li><a class="{{ Route::is('report/cumulative') ? 'active' : '' }}" href="{{route('report/cumulative')}}">Tracker - Cumulative</a></li>
                  <li><a class="{{ Route::is('reports/activity-tracker') ? 'active' : '' }}" href="{{route('reports/activity-tracker')}}">Tracker - Activity</a></li>
                </ul>
            </li>
                @if (auth()->user()->user_type==="ADMIN" || auth()->user()->user_type==="HELPDESK")
                <li class="{{ Route::is('onboard-schools') ? 'active' : '' }}">
                    <a href="{{route('onboard-schools')}}">                       
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 20V22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918C3 2.44405 3.44749 2 3.9985 2H16L20.9998 7V14H19V8H15V4H5V20H12ZM14.4646 19.4647L18.0001 23.0002L22.9498 18.0505L21.5356 16.6362L18.0001 20.1718L15.8788 18.0505L14.4646 19.4647Z"></path></svg>
                        <span>Onboard Schools</span>
                    </a>
                </li>
                @endif

               @if (auth()->user()->user_type==="ADMIN")
               <li class="{{ Route::is('/task-for-approval') ? 'active' : '' }}">
                    <a href="{{route('/task-for-approval')}}">                       
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 20V22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918C3 2.44405 3.44749 2 3.9985 2H16L20.9998 7V14H19V8H15V4H5V20H12ZM14.4646 19.4647L18.0001 23.0002L22.9498 18.0505L21.5356 16.6362L18.0001 20.1718L15.8788 18.0505L14.4646 19.4647Z"></path></svg>
                        <span>Task For Approval</span>
                    </a>
                </li> 
                
                <li class="{{ Route::is('users-list') ? 'active' : '' }}">
                    <a href="{{route('users-list')}}" >                      
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H18C18 18.6863 15.3137 16 12 16C8.68629 16 6 18.6863 6 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11Z"></path></svg>
                        <span>Users</span>            
                    </a>         
                </li>
                @endif
                <li>
                    <a href="javascript:void(0);" onclick="gnerate_activity_model()">                      
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V5a2 2 0 0 0-2-2zm-9 14H7v-2h3v2zm5-4H7v-2h8v2zm0-4H7V7h8v2z"/>
                    </svg>
                        <span>Generate Activity</span>            
                    </a>         
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--  END SIDEBAR  -->
