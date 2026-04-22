@extends('layouts.layout')
@section('content')
 <div class="main-container " id="container">
    <div class="overlay"></div>
    <div class="cs-overlay"></div>
    <div class="search-overlay"></div>
 <!--  BEGIN MAIN CONTAINER  -->
 <div class="main-container " id="container">

    <div class="overlay"></div>
    <div class="cs-overlay"></div>
    <div class="search-overlay"></div>
    
    @include('layouts.nav');
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
    
                                   <div class="widget-header">
                                        <div class="row">
                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                               
                                            </div>
                                        </div>
                                    </div>
                        <div class="text-center">
                            <div class="page-wrap d-flex flex-row align-items-center">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 text-center">
                                            <span class="display-1 d-block">404</span>
                                            <div class="mb-4 lead">The page you are looking for was not found.</div>
                                            <a href="#" class="btn btn-link">Back to Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>
                </div>
    
            </div>
      
    
        <!--  BEGIN FOOTER  -->
        <div class="footer-wrapper text-center">
            <div class="footer-section f-section-1">
                <p class="text-muted">COPYRIGHT ©  –  2024 ICT360 CLM, All Rights Reserved.</p>
            </div>
        </div>
        <!--  END FOOTER  -->
        
    </div>
    <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
   
@endsection
