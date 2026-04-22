@extends('layouts.layout')
@section('content')

<div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>

@include('layouts.nav')

<link rel="stylesheet" href="{{asset('public/css/filter-pop-up.css')}}">
  <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

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
                        <div class="row align-items-center my-2">
                            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                <h4 class="my-2 p-0">{{$data['title']}}</h4>
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
                                                <th >Sr No</th>
                                                <th>School Name</th>
                                                <th>School Contact</th>
                                                <th>School Email</th>
                                                <th>School City</th>
                                                <th>School Pincode</th>
                                                <th>Agreement Type</th>
                                            </tr>
                                        </thead>
                                          @include('layouts/spinner')
                                        <tbody id="showtableData">


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

        </div>

        <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content" id="mdlData">



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


@endsection

@section('scriptFun')


<script>
   $(document).ready(function(){
    $('#spinner').show();
    var RowID=$("#rowId").val();
    $.ajax({
        url:"{{ route('get-onboard-schools') }}",
        type: "POST",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{
        state:$("#stateData").val(),
        city:$("#Datacity").val(),
        trainer:$("#trainerData").val(),
    },
    beforeSend: function() {
                $("#loader").show();
            },
    success: function (response) {
        if(response){
        $("#showtableData").html(response.html);
        var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);
        }
    },
    complete: function() {
                $("#loader").hide();
            },
    error: function (xhr) {
        $("#loader").hide();
    console.log(xhr.responseText);
    }
    });
   });
</script>


@endsection
