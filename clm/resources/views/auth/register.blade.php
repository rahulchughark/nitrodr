@extends('layouts.layout')
@section('content')

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>


<style>
    .card .card-body {
        overflow: auto;
        padding-top: 5px;
        padding-bottom: 5px;
    }
     .login{
        height:100vh
    }
    .login .card{
        height: 100vh;
        border-radius: 0;
    }

    .header-container {
        display: none;
    }
    .footer-wrapper {
        position: fixed;
        left: 0;
        bottom: 0;
    }

    .form-control, .form-select {
        padding: 0.60rem 1.25rem!important;
    }

    .form-group label, label {
        margin-bottom: 0.3rem;
    }

</style>
<div class="container-fluid mx-auto align-self-center login">

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

        <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-6 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto m-0 p-0">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <form method="POST" action="{{ route('register') }}" class="w-100 p-3 px-md-5    ">
                        <div class="col-md-12 mb-2">
                            <p class="text-center"> <img src="{{ asset('public/images/ict-logo.png') }}" class="navbar-logo" height="50" alt="logo"></p>
                            <h2>{{ __('Register') }}</h2>
                        </div>
                        @csrf

                        <div class="form-group mb-2">
                            <label for="name">{{ __('Name') }}<span class="text-danger">*</span></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="email">{{ __('E-Mail Address') }}<span class="text-danger">*</span></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="password">{{ __('Password') }}<span class="text-danger">*</span></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="password-confirm" >{{ __('Confirm Password') }}<span class="text-danger">*</span></label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mb-2">
                            <label for="mobile" >{{ __('Contact') }}<span class="text-danger">*</span></label>
                            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" required autocomplete="Mobile number">
                        </div>
                        <div class="form-group mb-2">
                            <label for="user_type">{{ __('User Type') }}<span class="text-danger">*</span></label>
                            <select id="user_type" class="form-control form-select" name="user_type" required >
                            <option value="">Select option</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="FACULTY">FACULTY</option>
                            <option value="HELPDESK">HELPDESK</option>
                            <option value="SALES">SALES</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                            <a class="btn btn-primary" href="{{route('/dashboard')}}">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
<!-- <div class="container register-container">
    <div class="row justify-content-center">
       <div class="col-md-6">

                    <img src="{{ asset('public/images/clm-bg.png')}}" alt="auth-img">

       </div>
       <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                <h3 class="text-center">{{ __('Register') }}</h3>
            </div>
               
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}<span class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}<span class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}<span class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}<span class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('Contact') }}<span class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" required autocomplete="Mobile number">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="user_type" class="col-md-4 col-form-label text-md-right">{{ __('User Type') }}<span class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <select id="user_type" class="form-control form-select" name="user_type" required >
                                <option value="">Select option</option>
                                <option value="ADMIN">ADMIN</option>
                                <option value="FACULTY">FACULTY</option>
                                <option value="HELPDESK">HELPDESK</option>
                                <option value="SALES">SALES</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3 mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
