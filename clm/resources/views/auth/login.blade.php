@extends('layouts.app')

@section('content')

<style>
    .login{
        height:100vh
    }
    .login .card{
        height: 100vh;
        border-radius: 0;
    }
</style>

<div class="container-fluid mx-auto align-self-center login">

    <div class="row">

        <div class="col-12 col-md-6 col-lg-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
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

        <div class="col-xxl-5 col-xl-5 col-lg-5 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto m-0 p-0">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                <form method="POST" action="{{ route('login') }}" class="p-3 p-md-5">
                        @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                       <p class="text-center"> <img src="{{ asset('public/images/ict-logo.png') }}" class="navbar-logo" height="50" alt="logo"></p>
                            <h2>Sign In</h2>
                            <p>Enter your email and password to login</p>
                            
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                            <div>
                            <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                <!-- <button class="btn btn-primary default-button w-100 ">SIGN IN</button> -->
                                 @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        
                       
                        
                    </div>
                </form>    
                    
                </div>
            </div>
        </div>
        
    </div>
    
</div>




<!-- Dynamic login form below -->
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
