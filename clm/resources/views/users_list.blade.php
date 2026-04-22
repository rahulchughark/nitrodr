@extends('layouts.layout')
@section('content')

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
     

                               <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <h4>Users List</h4>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-end">
                                        <div class="btn-group align-items-center" >
                                    Create User<a href="{{ route('register') }}" class="btn btn-xs btn-light my-2 ms-1">
   <img src="{{ asset('public/images/add-1.svg') }}" alt="">
</a>

                                        </div>
                                    </div>
                                </div>
                    
                   
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <table id="style-1" class="table style-1 dt-table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>User Type</th>
                                                <th>Edit</th>
                                                <th>Transfer Data</th>
                                            </tr>
                                        </thead>
                                        @include('layouts/spinner')
                                        <tbody id="showdata">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

        </div>
        <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" id="mdlData"></div>
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
@endsection

@section('scriptFun')

<script>
    $(document).ready(function(){
     $.ajax({
         url:"{{ route('users-list-data') }}",
         cache: true,
         type: "GET",
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
    data:{},
    beforeSend: function() {
                $("#loader").show();
            },
     success: function (response) {
        $("#showdata").html(response);
        if(response){
        var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);
        }
   
     },
     complete: function() {
                $("#loader").hide();
            },
     error: function (xhr) {
     console.log(xhr.responseText);
     $("#loader").hide();
     }
     });
    });

    function edit_user_model(user_id,i)
    {
        if(i == 1){
            $.ajax({
                    url: "{{ route('edit-user-model') }}", 
                    type: "POST",
                    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                    data:{
                        user_id:user_id,
                        edit_type:'edit_user'
                    },
                    success: function (response) {
                        $("#mdlData").html(response);
                        $('#staticBackdrop').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
        }else if(i == 2){
            $.ajax({
                    url: "{{ route('edit-user-model') }}", 
                    type: "POST",
                    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
                    data:{
                        user_id:user_id,
                        edit_type:'transfer_data'
                    },
                    success: function (response) {
                        $("#mdlData").html(response);
                        $('#staticBackdrop').modal('show');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
        }
    }
 </script>

@endsection
