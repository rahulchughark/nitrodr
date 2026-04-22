@extends('layouts.layout')
@section('content')
       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">
<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>
@include('layouts.nav');
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

        @media (max-width: 767px){
            .multiselect-native-select {
                display: block;
                width: 100%;
            }

            .multiselect-native-select .btn-group {
                width: 100%;
            }

            .multiselect-native-select .btn-group .multiselect-container.dropdown-menu {
                max-width: 300px;
            }

            .multiselect-container.dropdown-menu label {
                text-wrap: wrap;
            }
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
     

                               <div class="widget-header">
                                    <div class="row align-items-center my-2">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-7">
                                            <h4 class="my-2 p-0">{{$title}}</h4>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-5 text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-light" id="filter-box">
                                                <img src="{{ asset('public/images/mdi--filter-menu.svg') }}" height="16" alt="" />

@if (!empty($createdF_date) || !empty($createdT_date) || !empty($F_due_date) || !empty($T_due_date) || !empty($partner_name))
<input type="hidden" id="createdF_date" value="{{@$createdF_date}}">
<input type="hidden" id="createdT_date" value="{{@$createdT_date}}">
<input type="hidden" id="F_due_date" value="{{@$F_due_date}}">
<input type="hidden" id="T_due_date" value="{{@$T_due_date}}">
<input type="hidden" id="partner_name" value="{{@$partner_name}}">
@endif

      </button>
      <div class="dropdown dropdown-lg">
        <div class="dropdown-menu1" id="filter-container">
        <form class="filter-bg" role="form" method="get">

            <div class="row">
                @if (App\Helpers\AuthHelper::users()->user_type==="ADMIN")
                <div class="mb-3 from-group">
                    <label for="multiselect" class="form-label font-weight-bold">Select Partner Name</label>
                    <select class="form-select form-control" id="#id55" name="partner_name">
                        <option value="">---Select Partner Name---</option>
 @if ($users->count()>0)
 @foreach ($users as $item)
 <option value="{{$item->id}}" {{$text=($item->id==$partner_name?"SELECTED":"")}}>{{$item->name}}</option>
 @endforeach
 @endif
                    </select>
                    </div>
                @endif

                <div class="col-6">
                <div class="mb-3">
                <label for="fromDate" class="form-label">Created From Date</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="fromDate" placeholder="Select From Date" name="created_from_date" value="{{$createdF_date ?? '' }}" />
                </div>
                </div>
                </div>
            
                <div class="col-6">
                <div class="mb-3">
                <label for="fromDate" class="form-label">Created To Date</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="toDate" placeholder="Select To Date" name="created_to_date" value="{{ $createdT_date ?? '' }}" />
                </div>
                </div>
                </div>
            
            
                <div class="col-6">
                    <div class="mb-3">
                    <label for="fromDate" class="form-label">From Follow-up Date</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="fromDueDate" placeholder="Select From Date" name="form_due_date" value="{{ $F_due_date ?? '' }}" />
                    </div>
                    </div>
                    </div>
                
                    <div class="col-6">
                    <div class="mb-3">
                    <label for="fromDate" class="form-label">To Follow-up Date</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="toDueDate" placeholder="SSelect To Date" name="to_due_date" value="{{ $T_due_date ?? '' }}" />
                    </div>
                    </div>
                    </div>
            
                </div>
                 
                <div class="mb-3 from-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{route('reports/activity-tracker')}}" class="btn btn-danger">Reset</a>
                </div>
            </form>
    </div>
    </div>
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
                                                <th>School Name</th>
                                                <th>Activites</th>
                                                <th>Completed</th>
                                                <th>Open</th>

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
  

    
    
</div>
<!--  END CONTENT AREA  -->
  <!--  BEGIN FOOTER  -->
  <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="text-muted">COPYRIGHT ©  –  <?=date("Y")?> ICT360 CLM, All Rights Reserved.</p>
        </div>
    </div>
    <!--  END FOOTER  -->
</div>
<!-- END MAIN CONTAINER -->
<!-- Button trigger modal -->

@endsection

@section('scriptFun')

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


    $(document).ready(function() {
        $('#fromDueDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function(selected) {
            var startDate = new Date(selected.date.valueOf());
            $('#toDueDate').datepicker('setStartDate', startDate);
        });
     
        $('#toDueDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function(selected) {
            var endDate = new Date(selected.date.valueOf());
            $('#fromDueDate').datepicker('setEndDate', endDate);
        });
    });

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('reports/activity-tracker-wise-data') }}",
            cache: true,
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
        created_f_date:$("#createdF_date").val(),
        created_t_date:$("#createdT_date").val(),
        f_due_date:$("#F_due_date").val(),
        t_due_date:$("#T_due_date").val(),
        partner_name:$("#partner_name").val(),
            },
            beforeSend: function() {
                $("#loader").show();
            },
            success: function(response) {
                $("#showdata").html(response);
                if (response) {
                    var script = document.createElement('script');
                    script.src = "{{ asset('public/js/paginate.js') }}";
                    document.head.appendChild(script);
                }
            },
            complete: function() {
                $("#loader").hide();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $("#loader").hide();
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
           $('#id55').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        maxHeight: 150
                    });
       
    });
</script>

@endsection
