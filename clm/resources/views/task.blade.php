@extends('layouts.layout')
@section('content')

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>
<input type="hidden" id="rowId" value="{{$getID}}">
<!------- filter section value set here---------->
<input type="hidden" id="createdF_date" value="{{$formData['createdF_date']}}">
<input type="hidden" id="createdT_date" value="{{$formData['createdT_date']}}">
<input type="hidden" id="F_due_date" value="{{$formData['F_due_date']}}">
<input type="hidden" id="T_due_date" value="{{$formData['T_due_date']}}">
<input type="hidden" id="ownerr" value="{{$formData['owner']}}">
<input type="hidden" id="subject" value="{{$formData['subject']}}">
<input type="hidden" id="getStatus" value="{{$formData['status']}}">
<!--------- end fiter section ------------------->
@include('layouts.nav')

<link rel="stylesheet" href="{{asset('public/css/filter-pop-up.css')}}">
  <!--  BEGIN CONTENT AREA  -->
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
            <!--  END BREADCRUMBS  -->

                               <div class="widget-header">
                                    <div class="row align-items-center my-2">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <h4 class="my-2 p-0">Tracker {{$renewalCond}} Task Wise</h4>
                                        </div>

   <!---------------------------------- start pop-up here -------------------------------->
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
    <input type="text" class="form-control" id="fromDate" placeholder="Select From Date"  name="created_from_date" value="{{$text=($formData['createdF_date']?$formData['createdF_date']:"")}}"/>
    </div>
    </div>
    </div>

    <div class="col-6">
    <div class="mb-3">
    <label for="fromDate" class="form-label">Created To Date</label>
    <div class="input-group">
    <input type="text" class="form-control" id="toDate" placeholder="Select To Date" name="created_to_date" value="{{$text=($formData['createdT_date']?$formData['createdT_date']:"")}}"/>
    </div>
    </div>
    </div>


    <div class="col-6">
        <div class="mb-3">
        <label for="fromDate" class="form-label">From Due Date</label>
        <div class="input-group">
        <input type="text" class="form-control" id="fromDueDate" placeholder="Select From Date" name="form_due_date" value="{{$text=($formData['F_due_date']?$formData['F_due_date']:"")}}"/>
        </div>
        </div>
        </div>
    
        <div class="col-6">
        <div class="mb-3">
        <label for="fromDate" class="form-label">To Due Date</label>
        <div class="input-group">
        <input type="text" class="form-control" id="toDueDate" placeholder="Select To Date" name="to_due_date" value="{{$text=($formData['T_due_date']?$formData['T_due_date']:"")}}"/>
        </div>
        </div>
        </div>


    </div>
    @if (App\Helpers\AuthHelper::users()->id===8)
    <div class="mb-3 from-group">
        <label for="multiselect" class="form-label">Select Task Owner</label>
        <select class="form-select form-control getSelected" id="id55" name="owner[]" multiple>
            @foreach ($resultUsers as $item)
            <option value="{{$item->id}}" {{$text=(in_array($item->id, $ownerArray) ? 'SELECTED' : '')}}>{{$item->name}}</option>
            @endforeach
        </select>
        </div>
    @endif
                    

    <div class="mb-3 from-group">
        <label for="multiselect" class="form-label font-weight-bold">Select Subject</label>
        <select class="form-select form-control" id="id56" name="subject[]" multiple>
            @foreach ($resultSubject as $item)
            <option value="{{$item->id}}" {{$text=(in_array($item->id, $subjectArray) ? 'SELECTED' : '')}}>{{$item->task}}</option>
            @endforeach
       
        </select>
        </div>

        <div class="mb-3 from-group">
            <label class="form-label font-weight-bold">Select Status</label>
            <select class="form-select form-control" name="status" id="statusValue">
            <option value="">---Select Status---</option>
            <option value="Not Started" {{$text=($formData['status']==="Not Started"?"SELECTED":"")}}>Not Started</option>
            <option value="In Progress" {{$text=($formData['status']==="In Progress"?"SELECTED":"")}}>In Progress</option>
            <option value="Completed" {{$text=($formData['status']==="Completed"?"SELECTED":"")}}>Completed</option>
            <option value="Re-scheduled" {{$text=($formData['status']==="Re-scheduled"?"SELECTED":"")}}>Re-scheduled</option>
            <option value="Cancelled" {{$text=($formData['status']==="Cancelled"?"SELECTED":"")}}>Cancelled</option>
            </select>
            </div>

    <div class="mb-3 from-group">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{route('/task')}}" class="btn btn-danger">Reset</a>
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
                                                <th >Sr No</th>
                                                <th>Subject</th>
                                                <th>School</th>
                                                <th>Owner</th>
                                                <th>Status</th>
                                                <th >Generated Date</th>
                                                <th >Due Date</th>
                                                <th >Updated By</th>
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
        url:"{{ route('report/task-data') }}",
        type: "POST",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{
        CheckID:RowID,
        created_f_date:$("#createdF_date").val(),
        created_t_date:$("#createdT_date").val(),
        f_due_date:$("#F_due_date").val(),
        t_due_date:$("#T_due_date").val(),
        owner:$("#ownerr").val(), 
        subject:$("#subject").val(),
        statusValue:$("#getStatus").val(),
        task_type:"{{$renewalCond}}",
    },

    beforeSend: function() {
                $("#loader").show();
            },
    success: function (response) {
        if(response){
            if(response){
        $("#showtableData").html(response.html);
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
        $("#loader").hide();
    console.log(xhr.responseText);
    }
    });
   });
</script>


<script>
    
    function task_model(task_id,lead_id,gen_task_id)
    {
        $.ajax({
                url: "{{ route('clmModelData') }}", 
                type: "POST",
                headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
                data:{
                    task_id:task_id,
                    lead_id:lead_id,
                    gen_task_id:gen_task_id,
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

    </script>

<script type="text/javascript">
    $(document).ready(function() {
           $('#id55').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        maxHeight: 150
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
    </script>

@endsection


