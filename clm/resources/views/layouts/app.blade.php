<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('public/images/title-icon.png')}}" type="image/x-icon">
    <title>ICT360 CLM</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Login') }}</title>

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
    
</head>
<body class="form">
<!-- <select id="example-getting-started" multiple="multiple">
    <option value="cheese">Cheese</option>
    <option value="tomatoes">Tomatoes</option>
    <option value="mozarella">Mozzarella</option>
    <option value="mushrooms">Mushrooms</option>
    <option value="pepperoni">Pepperoni</option>
    <option value="onions">Onions</option>
</select> -->

<div class="auth-container d-flex">

<!-- <div class="container mx-auto align-self-center">

    <div class="row">

        <div class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
            <div class="auth-cover-bg-image"></div>
            <div class="auth-overlay"></div>
                
            <div class="auth-cover">

                <div class="position-relative">

                    <img src="{{ asset('public/images/clm-bg.png')}}" alt="auth-img">

                    <h2 class="mt-5 text-black font-weight-bolder px-2">Welcome To ICT360 CLM Portal</h2>
                    <p class="text-black px-2">It is easy to setup with great customer experience.</p>
                </div>
                
            </div>

        </div>

        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                       <p class="text-center"> <img src="{{ asset('public/images/ict-logo.png') }}" class="navbar-logo" height="50" alt="logo"></p>
                            <h2>Sign In</h2>
                            <p>Enter your email and password to login</p>
                            
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-check-primary form-check-inline">
                                    <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                                    <label class="form-check-label" for="form-check-default">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-4">
                                <button class="btn btn-primary default-button w-100 ">SIGN IN</button>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-4">
                            <div class="">
                                <div class="seperator">
                                    <hr>
                                    <div class="seperator-text"> <span>Or continue with</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="text-center">
                                <p class="mb-0">Dont't have an account ? <a href="javascript:void(0);" class="text-warning">Sign Up</a></p>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
    
</div> -->

</div>


    <div id="app">
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                   
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    
                    <ul class="navbar-nav ml-auto">
                       
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> -->

        <main>
            @yield('content')
        </main>
    <!--</div> -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    
    
    <!-- <script src="{{ asset('public/src/plugins/src/global/vendors.min.js') }}"></script> -->
    <script src="{{ asset('public/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
    <script src="{{ asset('public/src/plugins/src/waves/waves.min.js') }}"></script>
    <script src="{{ asset('public/layouts/horizontal-light-menu/app.js') }}"></script>
    <script src="{{ asset('public/src/assets/js/custom.js') }}"></script>

    
    <!-- END GLOBAL MANDATORY SCRIPTS -->

</body>
</html>
