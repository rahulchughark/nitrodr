@extends('layouts.layout')
@section('content')

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>

<!------- filter section value set here---------->
{{-- <input type="hidden" id="createdF_date" value="{{@$formData['createdF_date']}}">
<input type="hidden" id="createdT_date" value="{{@$formData['createdT_date']}}">
<input type="hidden" id="F_due_date" value="{{@$formData['F_due_date']}}">
<input type="hidden" id="T_due_date" value="{{@$formData['T_due_date']}}">
<input type="hidden" id="ownerr" value="{{@$formData['owner']}}">
<input type="hidden" id="subject" value="{{@$formData['subject']}}">
<input type="hidden" id="getStatus" value="{{@$formData['status']}}">
<input type="hidden" id="rowId" value="{{@$getID}}"> --}}
<!--------- end fiter section ------------------->
@include('layouts.nav');

<link rel="stylesheet" href="{{asset('public/css/filter-pop-up.css')}}">
  <!--  BEGIN CONTENT AREA  -->

  <input type="hidden" value="{{ $data['encryptedString'] ?? ''}}" id="encryptedString">
  <input type="hidden" value="{{ $data['encryptedAssign'] ?? ''}}" id="encryptedAssign">

  <input type="hidden" value="{{ $data['stateF'] ?? ''}}" id="stateData">
  <input type="hidden" value="{{ $data['cityF'] ?? ''}}" id="Datacity">
  <input type="hidden" value="{{ $data['trainerF'] ?? ''}}" id="trainerData">

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
                                            <h4 class="my-2 p-0">{{$data['title']}}</h4>
                                        </div>

   <!---------------------------------- start pop-up here -------------------------------->
   <div class="col-xl-6 col-md-6 col-sm-6 col-6 text-end">
    <div class="btn-group">
@if (!$data['encryptedString'] && !$data['encryptedAssign'])
<button type="button" class="btn btn-xs btn-light" id="filter-box">
    <img src="{{ asset('public/images/mdi--filter-menu.svg') }}" height="16" alt="" />
    </button>
@endif
       

    <div class="dropdown dropdown-lg">
        <div class="dropdown-menu1" id="filter-container">
    <form method="get" class="filter-bg" role="form">

    <div class="row">

        <div class="mb-3 from-group">
            <label for="multiselect" class="form-label font-weight-bold">Select State</label>
            <select class="form-select form-control getCityByState" id="state" name="state">
                <option value="">---Select State--</option>
                @foreach ($data['state'] as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $data['stateF'] ? 'SELECTED' : '' }}>{{ $item->name }}</option>
                        @endforeach
            </select>
            </div>

            <div class="mb-3 from-group">
                <label for="cityData" class="form-label font-weight-bold">Select City</label>
                <select class="form-select form-control" id="cityData" name="city_name">
                    <option value="">---Select City---</option>
                    @if ($data['cityF']!='')
                    @foreach ($data['cites'] as $item)
                    <option value="{{$item->id}}"{{ $item->id == $data['cityF'] ? 'SELECTED' : '' }}>{{$item->city}}</option>
                @endforeach
                    @endif
                   
                </select>
            </div>
            <div class="mb-3 from-group">
            <label for="multiselect" class="form-label font-weight-bold">Select Agreement Type</label>
            <select class="form-select form-control" id="agreement_type" name="agreement_type">
                <option value="">---Select Agreement Type--</option>
                <option value="Fresh" {{ $data['agreement_typeF'] == 'Fresh' ? 'SELECTED' : '' }}>Fresh</option>
                <option value="Renewal" {{ $data['agreement_typeF'] == 'Renewal' ? 'SELECTED' : '' }}>Renewal</option>

            </select>
            </div>
                
@if (App\Helpers\AuthHelper::users()->user_type=='ADMIN' || App\Helpers\AuthHelper::users()->user_type=='SUPERADMIN')
<div class="mb-3 from-group">
    <label for="multiselect" class="form-label font-weight-bold">Select Trainer</label>
    <select class="form-select form-control" id="trainer" name="trainer">
        <option value="">---Select Trainer---</option>
        @foreach ($data['faculty'] as $item)
        <option value="{{ $item->id }}" {{ $item->id == $data['trainerF'] ? 'SELECTED' : '' }}>{{ $item->name }}</option>
        @endforeach
    </select>
    </div>
@endif
               


    </div>

    <div class="mb-3 from-group">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{route('reports/all-assign-task-schools')}}" class="btn btn-danger">Reset</a>
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
                                                <th>School Name</th>
                                                <th>School Contact</th>
                                                <th>School Email</th>
                                                <th>School City</th>
                                                <th>Agreement Type</th>
                                                <th>School Pincode</th>
                                                @if (App\Helpers\AuthHelper::users()->user_type=='ADMIN' || App\Helpers\AuthHelper::users()->user_type=='SUPERADMIN')
                                                <th>Assign Trainer</th> 
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


<!-- Modal for school -->
<div class="modal fade" id="exampleModalCenterSchool" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headName"></h5>
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
                                            <th>Trainer Name</th>
                                            <th>Trainer Email</th>
                                            <th>Trainer Contact</th>

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
   $(document).ready(function(){
    $('#spinner').show();
    var RowID=$("#rowId").val();
    $.ajax({
        url:"{{ route('reports/schools-task-assign') }}",
        type: "POST",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{
        encryptedString:$("#encryptedString").val(),
        encryptedAssign:$("#encryptedAssign").val(),
        state:$("#stateData").val(),
        city:$("#Datacity").val(),
        trainer:$("#trainerData").val(),
        agreement_type:$("#agreement_type").val(),
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


    function GetdataByTaskStatus(mstID, columnName) {
    $.ajax({
        url: "{{ route('reports/get-assign-trainer-name-by-school') }}",
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

    <script>
        $(document).on("change",".getCityByState",function(){
           var state=$(this).val();
           if(state!='' && state!=undefined){
            $.ajax({
         url:"{{ route('reports/get-cites-by-state') }}",
        type: "POST",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{
        stateString:state,
    },
    success: function (response) {
        if(response){
            if(response){
        $("#cityData").html(response);
        }
        }
    },
    error: function () {
                    console.log('Unable to fetch cities. Please try again.');
                }
            });
           }
           else {
            $('#cityData').html('<option value="">---Select City---</option>');
        }
        })
    </script>
    

@endsection


