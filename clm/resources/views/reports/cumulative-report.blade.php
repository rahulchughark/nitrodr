

@extends('layouts.layout')
@section('content')

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>

@include('layouts.nav');

<style>

    .table-auto div.dataTables_wrapper .table-responsive {
        max-height: 400px;
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

                               <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Tracker - Cumulative Report</h4>
                                        </div>
                                    </div>
                                </div>
                    
                   
                        <div class="row">
                        <div class="col-lg-12">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table id="style-1" class="table style-1 dt-table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Not started</th>
                                                    <th class="text-center">In progress</th>
                                                    <th class="text-center">Completed</th>
                                                    <th class="text-center">Re-scheduled</th>
                                                    <th class="text-center">Cancelled</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        <span style='cursor: {{ $not_started > 0 ? "pointer" : "default" }}'
                                                            data-bs-toggle='modal'
                                                            data-bs-target='{{ $not_started > 0 ? "#exampleModalCenterSchoolCount" : "" }}'
                                                            onclick='{{ $not_started > 0 ? "GetCountdataByTaskStatus(\"NS\",\"Not Started\", \"cumulative\")" : "return false;" }}'>
                                                        {{ $not_started }}
                                                    </span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span style='cursor: {{ $in_progress > 0 ? "pointer" : "default" }}'
                                                            data-bs-toggle='modal'
                                                            data-bs-target='{{ $in_progress > 0 ? "#exampleModalCenterSchoolCount" : "" }}'
                                                            onclick='{{ $in_progress > 0 ? "GetCountdataByTaskStatus(\"ip\",\"In Progress\", \"cumulative\")" : "return false;" }}'>
                                                        {{ $in_progress }}
                                                    </span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span style='cursor: {{ $completed > 0 ? "pointer" : "default" }}'
                                                            data-bs-toggle='modal'
                                                            data-bs-target='{{ $completed > 0 ? "#exampleModalCenterSchoolCount" : "" }}'
                                                            onclick='{{ $completed > 0 ? "GetCountdataByTaskStatus(\"com\",\"Completed\", \"cumulative\")" : "return false;" }}'>
                                                        {{ $completed }}
                                                    </span>
                                                    </td>


                                                    <td class="text-center">
                                                        <span style='cursor: {{ $re_sheduled > 0 ? "pointer" : "default" }}'
                                                            data-bs-toggle='modal'
                                                            data-bs-target='{{ $re_sheduled > 0 ? "#exampleModalCenterSchoolCount" : "" }}'
                                                            onclick='{{ $re_sheduled > 0 ? "GetCountdataByTaskStatus(\"rs\",\"Re-scheduled\", \"cumulative\")" : "return false;" }}'>
                                                        {{ $re_sheduled }}
                                                    </span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span style='cursor: {{ $cancelled > 0 ? "pointer" : "default" }}'
                                                            data-bs-toggle='modal'
                                                            data-bs-target='{{ $cancelled > 0 ? "#exampleModalCenterSchoolCount" : "" }}'
                                                            onclick='{{ $cancelled > 0 ? "GetCountdataByTaskStatus(\"can\",\"Cancelled\", \"cumulative\")" : "return false;" }}'>
                                                        {{ $cancelled }}
                                                    </span>
                                                    </td>
                                                </tr>


                                                <tr>
                                                
                                                    <tr>
                                                        <!-- Not Started -->
                                                        @if ($not_started > 0)
                                                            <td class="text-center">
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Not Started','School')">School</span> 
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Not Started','Task')">Task</span>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    
                                                        <!-- In Progress -->
                                                        @if ($in_progress > 0)
                                                            <td class="text-center">
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('In Progress','School')">School</span> 
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('In Progress','Task')">Task</span>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    
                                                        <!-- Completed -->
                                                        @if ($completed > 0)
                                                            <td class="text-center">
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Completed','School')">School</span> 
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Completed','Task')">Task</span>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    
                                                        <!-- Re-Scheduled -->
                                                        @if ($re_sheduled > 0)
                                                            <td class="text-center">
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Re-Scheduled','School')">School</span> 
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Re-Scheduled','Task')">Task</span>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    
                                                        <!-- Cancelled -->
                                                        @if ($cancelled > 0)
                                                            <td class="text-center">
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Cancelled','School')">School</span> 
                                                                <span data-bs-toggle="modal" data-bs-target="#exampleModalCenterSchool" class="shadow-none badge default-button pointerclass" onclick="GetdataByTaskStatus('Cancelled','Task')">Task</span>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    </tr>
                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
        <!-- Modal for school -->
        <div class="modal fade" id="exampleModalCenterSchool" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title setHeadvalue" id="exampleModalCenterTitle"></h5>
                                                    <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="statbox widget box box-shadow ">
                                                                <div class="widget-content widget-content-area ">
                                                                    <table id="popuppaginate-1" class="table popuppaginate-1 dt-table-hover mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 50px;">Sr No</th>
                                                                                <th id="taskC"></th>
                                                                                <th id="columnN"></th>
                                                                        
                                                                            </tr>
                                                                        </thead>
                                                                        <div id="spinner" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index: 1000;">
                                                                            <div class="spinner-border" role="status">
                                                                                <span class="sr-only"></span>
                                                                            </div>
                                                                        </div>
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
                                    </div>




                                    

                                    <!-- Modal for count data -->
<div class="modal fade" id="exampleModalCenterSchoolCount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                <table id="popuppaginate-2" class="table popuppaginate-2 dt-table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Task Subject</th>
                                            <th>School Name</th>
                                            <th>School Contact</th>
                                            <th>School Email</th>
                                            <th>City</th>
                                            <th>Pincode</th>
                                    
                                        </tr>
                                    </thead>
                                    <div id="spinnerSecound" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index: 1000;">
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


function GetCountdataByTaskStatus(mstID, columnName, forpage) {
    $.ajax({
        url: "{{ route('reports/trainer-wise-popup-data') }}",
        cache: true,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            mstID: mstID,
            status: columnName,
            forpage: forpage
        },
        beforeSend: function() {
            $("#headName").html('');
            $('#modalContent').html('');
            $("#spinnerSecound").show();
        },
        success: function(response) {
            if (response) {
                if ($.fn.DataTable.isDataTable('#popuppaginate-2')) {
                    $('#popuppaginate-2').DataTable().clear().destroy();
                }
                $("#headName").html(columnName);
                $('#modalContent').html(response);
                $('#exampleModalCenterSchoolCount').modal('show');

                // Dynamically load cumulative-popup.js
                var script = document.createElement('script');
                script.src = "{{ asset('public/js/cumulative-popup.js') }}";
                document.head.appendChild(script);
            }
        },
        complete: function() {
            $('#spinnerSecound').hide();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $('#spinnerSecound').hide();
        },
    });

    // Clear the DataTable and modal content when the modal is hidden
    $('#exampleModalCenterSchoolCount').on('hide.bs.modal', function() {
        if ($.fn.DataTable.isDataTable('#popuppaginate-2')) {
            $('#popuppaginate-2').DataTable().clear().destroy();
        }
        $("#modalContent").html('');  // Clear modal content
        $("#headName").html('');       // Clear header name
    });
}



    function GetdataByTaskStatus(type,typename){
       $.ajax({
        url:"{{route('report/cumulative-report-by-ajax')}}",
        cache: true,
         type: "POST",
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
    data:{checkStatus:type,forStatus:typename},
    beforeSend: function() {
        $("#showdata").html('');
                $("#spinner").show();
            },
    success: function (response) {
        if(typename==='Task'){
            $(".setHeadvalue").text("Task List");
            $("#taskC").text("Task Name");
        }
        else if(typename==='School'){
            $(".setHeadvalue").text("School List");
            $("#taskC").text("School Name");
        }
        switch(type) {
                case 'Not Started':
                   $("#columnN").text("Not Started");
                    break;
                case 'In Progress':
                $("#columnN").text("In Progress");
                    break;
                case 'Completed':
                $("#columnN").text("Completed");
                    break;
                    case 'Re-Scheduled':
                    $("#columnN").text("Re-Scheduled");
                    break;
                    case 'Cancelled':
                    $("#columnN").text("Cancelled");
                    break;
                default:
                    console.log('Something went wrong.!');
            }
            if(response=="error"){
                $("#showdata").html("<tr><td colspan='3' class='text-center text-danger'>Record not found.!</td></tr>");
                toastr.error('Record not found.!');
            }
            else{
                if ($.fn.DataTable.isDataTable('#popuppaginate-1')) {
                    $('#popuppaginate-1').DataTable().clear().destroy();
                }
                $("#showdata").html(response);
                    var script = document.createElement('script');
                    script.src = "{{ asset('public/js/popup-paginate.js') }}";
                    document.head.appendChild(script);
            }
     },
     complete: function() {
            $('#spinner').hide();
        },
     error: function (xhr) {
     console.log(xhr.responseText); 
     $('#spinner').hide();
     }
       });
    }
     var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);
</script>
                                    @endsection

                                    