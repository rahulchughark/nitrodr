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
                                            <h4 class="my-2 p-0">Tracker - Task Wise</h4>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-5 text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-light" id="filter-box">
                                                <img src="{{ asset('public/images/mdi--filter-menu.svg') }}" height="16" alt="" />
      </button>
      <div class="dropdown dropdown-lg">
        <div class="dropdown-menu1" id="filter-container">
        <form class="filter-bg" role="form" method="get">

                <div class="mb-3 from-group">
            <label for="multiselect" class="form-label w-100">Select Subject</label>
            <select class="form-select form-control" id="id55" name="subjectId[]" multiple>
                @foreach ($resultSubject as $item)
                <option value="{{$item->id}}" {{$text=(in_array($item->id,$getSubject)?"SELECTED":"")}}>{{$item->task}}</option>
                @endforeach
            </select>
            </div>

            <div class="mb-3 from-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{route('report/tracker_task_wise')}}" class="btn btn-danger">Reset</a>
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
                                                <th>Task subject</th>
                                                <th>Schools</th>
                                                <th>Not started</th>
                                                <th>In progress</th>
                                                <th>Completed</th>
                                                <th >Re-scheduled</th>
                                                <th >Cancelled</th>
                                                
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
            <p class="text-muted">COPYRIGHT ©  –  2024 ICT360 CLM, All Rights Reserved.</p>
        </div>
    </div>
    <!--  END FOOTER  -->
</div>
<!-- END MAIN CONTAINER -->
<!-- Button trigger modal -->



<!-- Modal for school -->
<div class="modal fade" id="exampleModalCenterSchool" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headName"></h5  >
                <h5 class="modal-title setHeadvalue" id="exampleModalCenterTitle"></h5>
                <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="statbox widget box box-shadow ">
                            <div class="widget-content widget-content-area table-auto">
                                <table id="popuppaginate-1" class="table popuppaginate-1 dt-table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>School Name</th>
                                            <th>School Contact</th>
                                            <th>School Email</th>
                                            <th>City</th>
                                            <th>Pincode</th>
                                    
                                        </tr>
                                    </thead>
                                    <div id="spinner" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index: 1000;">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                    <tbody id="modalContent">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                                                   
            </div>
        </div>
    </div>
</div>



@endsection

@section('scriptFun')

<script>
function GetdataByTaskStatus(mstID, columnName) {
    $.ajax({
        url: "{{ route('reports/tracker-wise-popup-data') }}",
        cache: true,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            mstID: mstID,
            status: columnName,
        },
        beforeSend: function() {
            $('#exampleModalCenterSchool').modal('hide');
            $('#modalContent').html('');
            $("#headName").html('');
            $("#spinner").show();
        },
        success: function(response) {
            if (response) {
                if ($.fn.DataTable.isDataTable('#popuppaginate-1')) {
                    $('#popuppaginate-1').DataTable().clear().destroy();
                }
                $("#headName").html(columnName);
                $('#modalContent').html(response);

                if ($('#modalContent').html().trim()) {
                    $('#exampleModalCenterSchool').modal('show');
                }
                var script = document.createElement('script');
                script.src = "{{ asset('public/js/popup-paginate.js') }}";
                document.head.appendChild(script);
            }
        },
        complete: function() {
            $('#spinner').hide();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
    $('#exampleModalCenterSchool').on('hide.bs.modal', function () {
        if ($.fn.DataTable.isDataTable('#popuppaginate-1')) {
            $('#popuppaginate-1').DataTable().clear().destroy();
        }
        $('#modalContent').html('');
        $("#headName").html('');
    });
}

    </script>



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
        $.ajax({
            url: "{{ route('report/tracker-task-wise-data') }}",
            cache: true,
            type: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                subject: $("#id55").val(),
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
