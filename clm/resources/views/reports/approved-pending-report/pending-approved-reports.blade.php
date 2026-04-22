@extends('layouts.layout')
@section('content')

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>
@if (session('message'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        };
        toastr["{{ session('alert-type') }}"]("{{ session('message') }}");
    </script>
@endif


@include('layouts.nav');

<link rel="stylesheet" href="{{asset('public/css/filter-pop-up.css')}}">

<style>

    .main-container {
        min-height: unset;
        height: calc(100vh - 100px);
    }

    div.dataTables_wrapper .table-responsive{
        max-height: calc(100vh - 350px);
        overflow: auto;
    }
    .table-auto div.dataTables_wrapper .table-responsive table tbody td {
            white-space: unset;
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

                 <!-- <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
                      <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Tracker Task Wise</h4>
                                        </div>
                                    </div>
                                </div>
                        <div class="widget-content widget-content-area "> -->

                               <div class="widget-header my-2">
                                    <div class="row align-items-center">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <h4>{{@$formData['pageName']==="automailerSection"?"No Action Activity Reports":"Task for Approval"}}</h4>
                                        </div>
<input type="hidden" id="createdF_date" value="{{$formData['createdF_date']}}">
<input type="hidden" id="createdT_date" value="{{$formData['createdT_date']}}">
<input type="hidden" id="F_due_date" value="{{$formData['F_due_date']}}">
<input type="hidden" id="T_due_date" value="{{$formData['T_due_date']}}">
<input type="hidden" id="subject" value="{{$formData['subject']}}">
<!----- add one field for no action report------>
<input type="hidden" id="task_id" value="{{@$formData['task_no_action']}}">
<input type="hidden" id="pagesection" value="{{@$formData['pageName']}}">
<!----- end section------>
                                   <!---------------------------------- start pop-up filter here -------------------------------->
   <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-end">
    <div class="btn-group">
        <button type="button" class="btn btn-xs btn-light" id="filter-box">
    <img src="{{ asset('public/images/mdi--filter-menu.svg') }}" height="16" alt="" />
    </button>
    <div class="dropdown dropdown-lg">
        <div class="dropdown-menu1" id="filter-container">
    <form method="get" class="filter-bg" role="form">

    <div class="row">

    <div class="col-6">
    <div class="mb-3">
    <label for="fromDate" class="form-label">Created From Date</label>
    <div class="input-group">
    <input type="text" class="form-control" id="fromDate" placeholder="Select From Date"  name="created_from_date" value="{{$text=($formData['createdF_date']?$formData['createdF_date']:'')}}"/>
    </div>
    </div>
    </div>

    <div class="col-6">
    <div class="mb-3">
    <label for="fromDate" class="form-label">Created To Date</label>
    <div class="input-group">
    <input type="text" class="form-control" id="toDate" placeholder="Select To Date" name="created_to_date" value="{{$text=($formData['createdT_date']?$formData['createdT_date']:'')}}"/>
    </div>
    </div>
    </div>


    <div class="col-6">
        <div class="mb-3">
        <label for="fromDate" class="form-label">From Due Date</label>
        <div class="input-group">
        <input type="text" class="form-control" id="fromDueDate" placeholder="Select From Date" name="form_due_date" value="{{$text=($formData['F_due_date']?$formData['F_due_date']:'')}}"/>
        </div>
        </div>
        </div>
    
        <div class="col-6">
        <div class="mb-3">
        <label for="fromDate" class="form-label">To Due Date</label>
        <div class="input-group">
        <input type="text" class="form-control" id="toDueDate" placeholder="Select To Date" name="to_due_date" value="{{$text=($formData['T_due_date']?$formData['T_due_date']:'')}}"/>
        </div>
        </div>
        </div>

    </div>
     
    @if (@$formData['pageName']!=="automailerSection")
    <div class="mb-3 from-group">
        <label for="multiselect" class="form-label font-weight-bold">Select Subject</label>
        <select class="form-select form-control" id="id56" name="subject[]" multiple>
            @foreach ($resultSubject as $item)
            <option value="{{$item->id}}" {{$text=(in_array($item->id,$subjectArray)?"SELECTED":"")}}>{{$item->task}}</option>
            @endforeach

        </select>
        </div>
    @endif

    <div class="mb-3 from-group">
    <button type="submit" class="btn btn-primary">Submit</button>
    @if (@$formData['pageName']==="automailerSection")
    <a href="{{url("/no-action-report",["type"=>"no_action_report","lead_id"=>@$formData['task_no_action']])}}" class="btn btn-danger">Reset</a>
    @else
    <a href="{{route('/task-for-approval')}}" class="btn btn-danger">Reset</a>
    @endif
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
                                              @if (@$formData['pageName']==="automailerSection")
                                                  <th>Assigned Spoc</th>
                                                  <th>Task Assigned</th>
                                                  <th>No Activity</th>
                                                  <th>In Progress</th>
                                                  <th>Completed</th>
                                                  <th>Re-scheduled</th>
                                                  <th>Cancelled</th>
                                                  
                                              @else
                                                <th>Task Subject</th>
                                                <th>School</th>
                                                <th>Owner</th>
                                                <th>Created Date</th>
                                                <th >Due Date</th>
                                                <th >Assigned Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                              @endif
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

@include('layouts.comman-popup');
<!-- Modal Start-->
<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
    
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Faculty List</h5>
                <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
              </div>

              <div class="modal-body">
                <form class="g-3" method="POST"  action="{{route('user-assign')}}">
             
                @csrf
                                             
                <div class="row align-items-center">
                                                <div class="mb-3 col">
                                                 <label for="colFormLabelSm" class="col-sm-4 fw-bold">Faculty:</label>
        
                                                     <select  class="form-select" id="userData" name="userID" required>
                                                        <option value="">---Select Faculty---</option>
                                                     </select>
                                             </div>
                                           
             <div class="col-auto text-end mt-2">
                 <button type="submit" class="btn btn-primary">Assign</button>
             </div>
            </div>

             <input type="hidden"  name="approvalId" id="approval_id">
             <input type="hidden"  name="mstTaskID" id="mstTaskID">
             </form>
               </div>
    
        </div>
      </div>
    </div>

@endsection

@section('scriptFun')

<script>
    var popupPaginateUrl = "{{ asset('public/js/popup-paginate.js') }}";
</script>

   <script src="{{asset('public/js/comman-popup.js')}}">

</script>

<script>
   $(document).ready(function(){
    $.ajax({
        url:"{{ route('get-task-approval-report-ajax') }}",
        type: "POST",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{
        created_f_date:$("#createdF_date").val(),
        created_t_date:$("#createdT_date").val(),
        f_due_date:$("#F_due_date").val(),
        t_due_date:$("#T_due_date").val(),
        subject:$("#subject").val(),
        taskID:$("#task_id").val(),
        pageSection:$("#pagesection").val(),
    },
    beforeSend: function() {
                $("#loader").show();
            },
    success: function (response) {
        if(response){
            if(response){
        $("#showtableData").html(response);
        var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);
        }
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

   function task_model(task_id, mstID) {
    $.ajax({
        url: "{{ route('get-clm-users') }}", 
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
        success: function (response) {
            $("#approval_id").val(task_id);
            $("#mstTaskID").val(mstID);
            $('#staticBackdrop').modal('show');
            $('#userData').empty();
            $('#userData').append('<option value="">---Select User---</option>');
            $.each(response, function(key, value) {
                if (value.id !== 8) {
                    $('#userData').append('<option value="' + value.id + '">' + value.name + '</option>');
                }
            });
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
}

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
           $('#id56').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        maxHeight: 150
                    });
       
    });
</script>



@endsection
