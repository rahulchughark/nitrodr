
@extends('layouts.layout')
@section('content')

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>

@include('layouts.nav')
<link rel="stylesheet" href="{{asset('public/css/filter-pop-up.css')}}">

<style>

    .table-auto div.dataTables_wrapper .table-responsive {
        max-height: 400px;
        overflow: auto;
    }
    .table-auto div.dataTables_wrapper .table-responsive table tbody td {
            white-space: unset;
        }
        .table > tbody > tr > td {
            padding-right: 45px!important;
        }
</style>
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
                                    <div class="row align-items-center my-2">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-7">
                                            <h4 class="my-2 p-0">Tracker - Trainer wise</h4>
                                        </div>
<input type="hidden" id="trainerId" value="{{$trainerData['traninerName']}}">
                                         <!---------------------------------- start pop-up here -------------------------------->
   <div class="col-xl-6 col-md-6 col-sm-6 col-5 text-end">
    <div class="btn-group">
        @if (App\Helpers\AuthHelper::users()->id===8)
        <button type="button" class="btn btn-xs btn-light" id="filter-box">
    <img src="{{ asset('public/images/mdi--filter-menu.svg') }}" height="16" alt="" />
    </button>
@endif
<div class="dropdown dropdown-lg">
    <div class="dropdown-menu1" id="filter-container">
    <form method="get" class="filter-bg" role="form">
       
    <div class="mb-3 from-group">
        <label for="multiselect" class="form-label font-weight-bold">Select Trainer</label>
        <select class="form-select form-control" id="id56" name="trainer[]" multiple>
            @foreach ($resultUsers as $item)
            <option value="{{$item->id}}" {{$text=(in_array($item->id, $trainer)?"SELECTED":"")}}>{{$item->name}}</option>
            @endforeach
        </select>
        </div>

    <div class="mb-3 from-group">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{route('report/trainer')}}"  class="btn btn-danger">Reset</a>
    </div>
    </form>
    </div>
    </div>
    </div>
   </div>
   <!------------------------------- end pop-up here-------------------------------------->
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
                                                <th>Trainer Name</th>
                                                <th>Schools</th>
                                                <th>Task</th>
                                                <th>Not started</th>
                                                <th>In progress</th>
                                                <th>Completed</th>
                                                <th>Re-scheduled</th>
                                                <th>Cancelled</th>
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
@include('layouts.comman-popup')

@endsection

@section('scriptFun')
<script>
    var popupPaginateUrl = "{{ asset('public/js/popup-paginate.js') }}";
</script>

   <script src="{{asset('public/js/comman-popup.js')}}"></script>
  <script>

$(document).ready(function(){
     $.ajax({
         url:"{{ route('report/trainer-report-by-ajax') }}",
         cache: true,
         type: "POST", 
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
    data:{
        trainer:$("#trainerId").val(),
    },
    beforeSend: function() {
                $("#loader").show();
            },
     success: function (response) {
        if(response){
        var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);
            $("#showdata").html(response);
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
  </script>

  

<script type="text/javascript">
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
    $(document).ready(function() {
           $('#id56').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        maxHeight: 150
                    });
       
    });
</script>

@endsection