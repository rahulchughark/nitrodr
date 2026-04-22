@extends('layouts.layout')
@section('content')
@include('modals.program_initiation_modal')
<style>
    .table > tbody > tr > td {
        white-space: unset;
    }

    #content {
        padding-bottom: 50px;
    }

    .tab-content {
        height: calc(100vh - 260px);
        overflow-y: auto;
        overflow-x: hidden;
    }
    .new-tab-activity-tracker .table-responsive {
        height: auto!important;
    }
</style>

       <!--  BEGIN MAIN CONTAINER  -->
       <div class="main-container " id="container">

<div class="overlay"></div>
<div class="cs-overlay"></div>
<div class="search-overlay"></div>

@include('layouts.nav')
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="container">
        <div class="mt-2">

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
                                            <h4>View Leads</h4>
                                        </div>
                                    </div>
                                </div>
                 <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
                      <div class="statbox widget box box-shadow">
                                <!-- <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>View Leads</h4>
                                        </div>
                                    </div>
                                </div> -->
                        <div class="widget-content widget-content-area ">

                        <div class="simple-pill tab_leads">
                                       <div class="row">
                                        <div class="col-md">
                                             
                                          <ul class="nav nav-pills " id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
											<button class="nav-link <?=($tab!="activity" && $tab!="task"?"active":"")?>" id="pills-home-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-home-icon" type="button" role="tab" aria-controls="pills-home-icon" aria-selected="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3V19H21V21H3V3H5ZM20.2929 6.29289L21.7071 7.70711L16 13.4142L13 10.415L8.70711 14.7071L7.29289 13.2929L13 7.58579L16 10.585L20.2929 6.29289Z"></path></svg>
                                                    Leads
                                                </button>

                                        <!------ add new tab for Products allign ----->
                                        {{--
                                            <li class="nav-item " role="presentation">
                                                <button class="nav-link <?=($tab==="product"?"active":"")?>" id="pills-product-icon-tabb" data-bs-toggle="pill" data-bs-target="#pills-product-icon" type="button" role="tab" aria-controls="pills-product-icon" aria-selected="false">
                                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM8 7H16V9H8V7ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"></path></svg>
                                                      Products
                                                  </button>
                                              </li>
											--}}	
                                        <!------ add new tab for tasks allign ----->
                                            </li>
                                            <li class="nav-item" role="presentation">
											  <button class="nav-link <?=($tab==="task"?"active":"")?>" id="pills-profile-icon-tabb" data-bs-toggle="pill" data-bs-target="#pills-profile-icon" type="button" role="tab" aria-controls="pills-profile-icon" aria-selected="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM8 7H16V9H8V7ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"></path></svg>
                                                    Tasks
                                                </button>
                                            </li>

                                            <!------ add new tab for Activity tracker ----->
                                            <li class="nav-item " role="presentation">
                                                <button class="nav-link <?=($tab==="activity"?"active":"")?>" id="pills-activity-icon-tabb" data-bs-toggle="pill" data-bs-target="#pills-activity-icon" type="button" role="tab" aria-controls="pills-activity-icon" aria-selected="false">
                                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM8 7H16V9H8V7ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"></path></svg>
                                                      Activites
                                                  </button>
                                              </li>
                                            <!------------- end activity tracker-------------------->
                                           
                                        </ul>
                                        </div>
                                        <div class="col-md-auto my-2 my-md-0">
                                        {{-- <button class="btn btn-primary py-1 btn-small" type="button" style="float: right;" onclick="task_model(9,{{$lead_id}},0)" id="text-set"></button> --}}

                                        <span id="text-set"></span>

                                        <span id="set-export-svg"></span>
                                        

                                        </div>
                                       </div>
                                       <div class="tab-content" id="myTabContent">
                                             <div class="tab-pane fade <?=($tab!="activity" && $tab!="task"?"show active":"")?>" id="pills-home-icon" role="tabpanel" aria-labelledby="pills-home-icon-tab" tabindex="0">
                                               
												<div class="table-responsive "> 
                                                    <table class="table table-bordered"> 
                                                    <tbody> 
                                                        @if (isset($result))
                                                        
                                                            <tr> 
                                                                <td width="35%">Reseller Name</td> 
                                                                <td width="65%">{{$text=$result->reseller_name?$result->reseller_name:"N/A"}}</td> 
                                                            </tr> 
                                                            <tr> 
                                                                <td>Reseller Email</td> 
                                                                <td> {{$text=$result->reseller_email?$result->reseller_email:"N/A"}} 
                                                                    
                                                                </td> </tr>
                                                                 <tr> 
                                                                    <td>Submitted By</td> 
                                                                    <td> {{$text=$result->submitted_by?$result->submitted_by:"N/A"}} </td>
                                                                 </tr> </tbody> </table> 
                                                                </div>
    
                                                                <div class="table-responsive mt-2">
        <table class="table table-bordered">
    
        <tbody>
                                                            <tr>
                                                                    <td width="35%">Product Name</td>
                                                                    <td width="65%">{{$text=$result->product_name?$result->product_name:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Sub-Product Name</td>
                                                                    <td>{{$text=$result->product_type?$result->product_type:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Date of Visit</td>
                                                                    <td>{{$text=$result->created_date?$result->created_date:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Lead Source</td>
                                                                    <td width="65%">{{$text=$result->lead_source?$result->lead_source:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Sub Lead Source</td>
                                                                    <td width="65%">{{$text=$result->sub_lead_source?$result->sub_lead_source:"N/A"}}</td>
                                                                </tr>                                                         
                                                                <tr>
                                                                    <td>School Board</td>
                                                                    <td>{{$text=$result->school_board?$result->school_board:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Organization Name</td>
                                                                    <td>{{$text=$result->organization_name?$result->organization_name:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Is Group</td>
                                                                    <td>{{$text=$result->is_group?$result->is_group:"N/A"}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Group Name</td>
                                                                    <td>{{$text=$result->group_name?$result->group_name:"N/A"}}</td>
                                                                </tr>                                                            
                                                                <tr>
                                                                    <td>State</td>
                                                                    <td>{{$text=($result->state_name?$result->state_name:"N/A")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>City</td>
                                                                    <td>{{$text=($result->city_name?$result->city_name:"N/A")}}</td>
                                                                 </tr>
                                                               <tr>
                                                                    <td>Address</td>
                                                                    <td>{{$text=($result->address?$result->address:"N/A")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Pin Code</td>
                                                                    <td>{{$text=($result->pincode?$result->pincode:"N/A")}}</td>
                                                                </tr>
                                                            </tbody>
                                                </table>
                                            </div>

                                        <div class="table-responsive mt-0">
                                            <table class="table table-bordered">
                                        <tbody>
                                                                <tr>
                                                                    <td width="35%">Full Name</td>
                                                                    <td width="65%"> {{$text=($result->eu_name?$result->eu_name:"N/A")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Email</td>
                                                                    <td>{{$text=($result->eu_email?$result->eu_email:"N/A")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Landline Number</td>
                                                                    <td>{{$text=($result->eu_landline?$result->eu_landline:"N/A")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Mobile</td>
                                                                    <td>{{$text=($result->eu_mobile?$result->eu_mobile:"N/A")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Designation</td>
                                                                    <td>{{$text=($result->eu_designation?$result->eu_designation:"N/A")}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                    
                                                                    <td>Program Initiation Date</td>
                                                                    <td>{{$text=($result->program_initiation_date?$result->program_initiation_date:"N/A")}}
                                                                    @if(empty($programInitiationDate))
                                                                        <!-- <button class="btn btn-sm btn-outline-primary" onclick="openProgramModal()">Insert Program Initiation Date</button> -->
                                                                        <button class="btn btn-small btn-primary" data-bs-toggle="modal" data-bs-target="#openProgram">Insert Program Initiation Date</button>
                                                                    @endif
                                                                    </td>
                                                                </tr>
                                                        
                                                        
                                                        @endif
                                                       
                                                        </tbody>
</table>
                                                        </div>
                                                        @if(Auth::user()->user_type == 'ADMIN' || Auth::user()->user_type == 'HELPDESK')

                                    <!-- <a data-toggle="modal" onclick="edit_clm({{$lead_id}})" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-2 ms-3 mb-3">Edit</button></a> -->
                                    <a data-toggle="modal" onclick="edit_lead_clm({{$lead_id}})" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-2 ms-3 mb-3">Edit Lead</button></a>
                                    @endif
										
                                            </div>
                                             <div class="tab-pane fade <?=($tab==="task"?"show active":"")?>"  id="pills-profile-icon" role="tabpanel" aria-labelledby="pills-profile-icon-tab" tabindex="0">
                                              
                                                <div class="table-responsive">


                                                @php
                                                $getGenerateTask = App\GeneratedTask::select(
                                        "generated_task.*",
                                        "mst_task.task",
                                        "clm_users.name as updated_by",
                                        "process.financial_year_start",
                                        "process.financial_year_end"
                                    )
                                    ->leftJoin("mst_task", "mst_task.id", "=", "generated_task.mst_task_id")
                                    ->leftJoin("clm_users", "clm_users.id", "=", "generated_task.updated_by")
                                    ->leftJoin("tbl_renewal_lead_task_process_record as process", "process.id", "=", "generated_task.renewal_tasks_process_id")
                                    ->whereNotNull("generated_task.task_owner")
                                    ->where("generated_task.lead_id", $lead_id);

                                // Role-based filter
                                if (in_array(App\Helpers\AuthHelper::users()->user_type, ['FACULTY', 'SALES', 'HELPDESK'])) {
                                    $getGenerateTask->where("generated_task.task_owner", App\Helpers\AuthHelper::users()->id);
                                }

                                $getGenerateTask = $getGenerateTask->get()->groupBy(function ($task) {
                                    return $task->financial_year_start . '-' . $task->financial_year_end;
                                });
                                $getGenerateTask = $getGenerateTask->sortKeysDesc();

                                                @endphp
                                                @if ($getGenerateTask->count() > 0)
    <!-- Tab Navigation -->
    <ul class="nav nav-pills mt-3 mb-3" id="financialYearTabs" role="tablist">
        @foreach ($getGenerateTask as $fy => $tasks)
            <li class="nav-item " role="presentation">
                <button class="nav-link shadow-none @if ($loop->first) active @endif" id="tab-{{ $loop->index }}" data-bs-toggle="tab" href="#content-{{ $loop->index }}" role="tab">
                    FY {{ $fy }}
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        @foreach ($getGenerateTask as $fy => $tasks)
            <div class="tab-pane fade @if ($loop->first) show active @endif" id="content-{{ $loop->index }}" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:70px">Sr. No.</th>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" onclick="task_model({{ $task->mst_task_id }}, {{ $task->lead_id }}, {{ $task->id }})" data-bs-target="#staticBackdrop" style="cursor:pointer">
                                            {{ $task->task_subject ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>{{ $task->task_status ?? 'N/A' }}</td>
                                    <td>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ $task->task_generate_date ? date("d-m-Y", strtotime($task->task_generate_date)) : "N/A" }}
                                    </td>
                                    <td>{{ $task->updated_by ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-danger">Record not found.!</div>
@endif


                                                </div>
                                            </div>

<!------------ new tab for activity tracker -------->
<div class="tab-pane fade new-tab-activity-tracker <?=($tab==="activity"?"show active":"")?>" id="pills-activity-icon" role="tabpanel" aria-labelledby="pills-profile-icon-tab" tabindex="0">
                                              

<table id="style-1" class="table table-bordered style-1">
<thead>
<tr>
<th style="width:70px">Sr. No.</th>
<th scope="col">Activity Date</th>
<th scope="col">School Name</th>
<th scope="col">Subject</th>
<th scope="col">Faculty Name</th>
<th scope="col">POC Name - Designation</th>
<th scope="col">Follow-up Date</th>
<th scope="col">Provided Date</th>
<th scope="col">Follow-up reason</th>
<th scope="col">Status</th>
<th scope="col">Action</th>
</tr>
</thead>
<tbody>
@php
$i=0;
$getclm_activity=App\CLMActivity::select("clm_activity.id as clm_activity_id","clm_activity.subject", "clm_activity.poc_name", "clm_activity.poc_designation", "clm_activity.follow_up_date", "clm_activity.solution_rovided_date", "clm_activity.follow_up_reason", "clm_activity.created_at", "clm_users.name", "o.school_name", DB::raw("CASE WHEN clm_activity.status = 1 THEN 'Open' ELSE 'Closed' END as status_label"))
->leftjoin("clm_users","clm_users.id","=","clm_activity.created_by")
->leftjoin("orders as o","o.id","=","clm_activity.lead_id")
->whereNotNull("clm_activity.created_by");
if(App\Helpers\AuthHelper::users()->user_type!=='ADMIN'){
    $getclm_activity->where([["clm_activity.created_by","=",App\Helpers\AuthHelper::users()->id],["lead_id","=",$lead_id]]);
 }else if(App\Helpers\AuthHelper::users()->user_type==='ADMIN'){//&& request('checktrue')=='condition'
 if(request('checktrue')=='condition'){
    if( request('flag')==="closed"){
        $checkStatus=0;
        $getclm_activity->where([["clm_activity.status","=",$checkStatus]]);
    }else if(request('flag')==="open"){
        $checkStatus=1;
        $getclm_activity->where([["clm_activity.status","=",$checkStatus]]);
    }
 }

    $getclm_activity->where([["lead_id","=",$lead_id]]);
 }
$getAllResult=$getclm_activity->orderBy("clm_activity.id", "DESC")->get();

@endphp
@if ($getAllResult->count()>0)
@foreach ($getAllResult as $key => $clm_activity )
<tr>
<td>{{++$i}}</td>
<td><?= ($clm_activity->created_at?date("d/m/Y",strtotime($clm_activity->created_at)):"N/A")?></td>
<td><?= ($clm_activity->school_name?$clm_activity->school_name:"N/A")?></td>
<td><?= ($clm_activity->subject?$clm_activity->subject:"N/A")?></td>
<td><?= ($clm_activity->name?$clm_activity->name:"N/A")?></td>
<td><?= ($clm_activity->poc_name?$clm_activity->poc_name:"N/A")."-".($clm_activity->poc_designation?$clm_activity->poc_designation:"N/A")?></td>
<td><?= ($clm_activity->follow_up_date?date("d/m/Y",strtotime($clm_activity->follow_up_date)):"N/A") ?></td>
<td><?= ($clm_activity->solution_rovided_date?date("d/m/Y",strtotime($clm_activity->solution_rovided_date)):"N/A") ?></td>
<td><?= ($clm_activity->follow_up_reason?$clm_activity->follow_up_reason:"N/A")?></td>
<td><?= ($clm_activity->status_label?$clm_activity->status_label:"N/A")?></td>
<td>
    @if ($clm_activity->status_label==="Open")
    <a href="javascript:void(0)" onclick="EditActitvity(<?=  $clm_activity->clm_activity_id?>,'forEdit',<?=$lead_id?>)" title="Edit Activity"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
        <path d="M3 21H5.5L17.5 9L15 6.5L3 18.5V21ZM20.71 7.04C21.1 6.65 21.1 6.03 20.71 5.64L18.36 3.29C17.97 2.9 17.35 2.9 16.96 3.29L15 5.25L18.75 9L20.71 7.04Z"/>
    </svg></a>
    @endif
   <a href="javascript:void(0)" onclick="GetdataByActivityTracker(<?=  $clm_activity->clm_activity_id?>,'forView',<?=$lead_id?>)" title="View Activity"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
    <path d="M12 4.5C7.5 4.5 4.05 7.61 2 12C4.05 16.39 7.5 19.5 12 19.5C16.5 19.5 19.95 16.39 22 12C19.95 7.61 16.5 4.5 12 4.5ZM12 17.5C9.515 17.5 7.5 15.485 7.5 13C7.5 10.515 9.515 8.5 12 8.5C14.485 8.5 16.5 10.515 16.5 13C16.5 15.485 14.485 17.5 12 17.5ZM12 10C10.62 10 9.5 11.12 9.5 12.5C9.5 13.88 10.62 15 12 15C13.38 15 14.5 13.88 14.5 12.5C14.5 11.12 13.38 10 12 10Z"/>
</svg></a>
</td>
</tr>
@endforeach
@else
<tr>
<td colspan="9" class="text-danger text-center">Record not found.!</td>
</tr>
@endif


</tbody>
</table>
</div>
<!--------- end this section ------------------->
<!--------- Product section ------------------->

<div class="tab-pane fade <?=($tab==="product"?"show active":"")?>"  id="pills-product-icon" role="tabpanel" aria-labelledby="pills-product-icon-tab" tabindex="0">
                                              
@php
        $financialYears = App\LeadProductOpportunity::select('financial_year_start', 'financial_year_end')
            ->where('lead_id', $lead_id)
            ->where('status', '!=', 0)
            ->distinct()
            ->orderBy('financial_year_start', 'asc')
            ->get();
    @endphp

    <div id="collapseFinancialYearProducts" class="collapse show">
        @foreach($financialYears as $fy)
            @php
                $products = App\LeadProductOpportunity::select(
                        "tbl_lead_product_opportunity.id",
                        "tbl_lead_product_opportunity.product",
                        "tbl_lead_product_opportunity.quantity",
                        "tbl_lead_product_opportunity.original_sales_price",
                        "tbl_lead_product_opportunity.unit_price",
                        "tbl_lead_product_opportunity.total_price",
                        "tbl_product_opportunity.product_name"
                    )
                    ->join("tbl_product_opportunity", "tbl_product_opportunity.id", "=", "tbl_lead_product_opportunity.product")
                    ->where("tbl_lead_product_opportunity.status", "!=", 0)
                    ->where("tbl_lead_product_opportunity.lead_id", $lead_id)
                    ->where("tbl_lead_product_opportunity.financial_year_start", $fy->financial_year_start)
                    ->orderBy("tbl_lead_product_opportunity.created_at", "asc")
                    ->get();

                $grandTotal = $products->sum('total_price');
            @endphp

            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="6">Financial Year ({{ $fy->financial_year_start }} - {{ $fy->financial_year_end }})</th>
                        </tr>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Product Code</th>
                            <th>Quantity</th>
                            <th>Original Price</th>
                            <th>Negotiate/Sales Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ number_format($product->original_sales_price, 2) }}</td>
                                <td>{{ number_format($product->unit_price, 2) }}</td>
                                <td>{{ number_format($product->total_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-danger">No products found for this financial year.</td>
                            </tr>
                        @endforelse
                    {{--    <tr class="font-weight-bold">
                            <td colspan="5">Grand Total</td>
                            <td>{{ number_format($grandTotal, 2) }}</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
                                          </div>

<!---------end Product section ------------------->


                                        </div>
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
            <p class="text-muted">COPYRIGHT ©  –  2024 ICT360 CLM, All rights reserved.</p>
        </div>
    </div>
    <!--  END FOOTER  -->
    
</div>
<!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->
<!-- Button trigger modal -->

<!-- Modal for Activity tracker -->
<div class="modal fade" id="ActivityTrackerModal" tabindex="-1" role="dialog" aria-labelledby="abcModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Activity</h5>
                <h5 class="modal-title setHeadvalue" id="exampleModalCenterTitle"></h5>
                <button type="button" class="btn-close btn_modal_close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive ">
                    <table class="table table-bordered">
                        <tbody id="modalContent">                                                                            
                        </tbody>
                    </table>
                </div>                                           
            </div>
        </div>
    </div>
</div>

<!-- End Modal for Activity tracker -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content" id="mdlData">

    </div>
  </div>
</div>

<!----edit activity modal--->
<div class="modal fade" id="editActivity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content" id="mdlActivityData">
    
        </div>
      </div>
    </div>
<!-- end activity modal---->

@endsection

@section('scriptFun')
<script>

$(document).ready(function () {  
    const urlParams = new URLSearchParams(window.location.search);
    const tabValue = urlParams.get('tab');
    const flag = urlParams.get('flag');
    const checktrue = urlParams.get('checktrue');
    var queryParams = new URLSearchParams({
        tab: tabValue,
        flag: flag,
        checktrue: checktrue
    }).toString();
    if(tabValue=='activity'){
        var id = {{ $lead_id }};
        var baseUrl  = id ? '{{ route("export") }}/' + id : '{{ route("export") }}';
        var url = baseUrl + '?' + queryParams;
        $("#text-set").html('<button class="btn btn-primary py-1 btn-small" type="button" onclick="task_model(9,{{$lead_id}},0)">Generate Activity</button>');
        $("#set-export-svg").html(
    '<a href="' + url + '" class="btn btn-primary py-1 px-1 btn-small" title="Export Data">' +
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">' +
    '<path d="M21 3H3C2.44772 3 2 3.44772 2 4V20C2 20.5523 2.44772 21 3 21H21C21.5523 21 22 20.5523 22 20V4C22 3.44772 21.5523 3 21 3ZM12 16C10.3431 16 9 14.6569 9 13H4V5H20V13H15C15 14.6569 13.6569 16 12 16ZM16 11H13V14H11V11H8L12 6.5L16 11Z"></path>' +
    '</svg>' +
    '</a>'
);

    }
    {{--
    else if(tabValue=='task'){
        $("#text-set").html('<button class="btn btn-primary py-1 btn-small" type="button" onclick="task_model(9,{{$lead_id}},0)">Generate Tool Training</button>');
        $("#set-export-svg").html('');
    }
--}}
     @if($getclm_activity->count() > 0)
        var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);
        @endif
     
    $("[id^='pills-']").on('click', function () {
        const clickedTab = $(this).attr("id");
        setTimeout(() => {
            if (clickedTab === "pills-home-icon-tab") {
                $("#text-set").html('');
            } 
            if (clickedTab === "pills-profile-icon-tabb") {
    {{--            $("#text-set").html('<button class="btn btn-primary py-1 btn-small" type="button" onclick="task_model(9,{{$lead_id}},0)">Generate Tool Training</button>');
                $("#set-export-svg").html(''); --}}
                @if($getGenerateTask->count() <= 0)
                toastr.error("Task record not found!");
                @endif
            } 
            else if (clickedTab === "pills-activity-icon-tabb") {
                var id = {{ $lead_id }};
               var url = id ? '{{ route("export") }}/' + id : '{{ route("export") }}';
        $("#text-set").html('<button class="btn btn-primary py-1 btn-small" type="button" onclick="task_model(9,{{$lead_id}},0)">Generate Activity</button>');
        $("#set-export-svg").html(
    '<a href="' + url + '" class="btn btn-primary py-1 px-1 btn-small" title="Export Data">' +
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">' +
    '<path d="M21 3H3C2.44772 3 2 3.44772 2 4V20C2 20.5523 2.44772 21 3 21H21C21.5523 21 22 20.5523 22 20V4C22 3.44772 21.5523 3 21 3ZM12 16C10.3431 16 9 14.6569 9 13H4V5H20V13H15C15 14.6569 13.6569 16 12 16ZM16 11H13V14H11V11H8L12 6.5L16 11Z"></path>' +
    '</svg>' +
    '</a>'
);
if ($.fn.DataTable.isDataTable('#style-1')) {
    $('#style-1').DataTable().destroy();
                }

        var script = document.createElement('script');
        script.src = "{{ asset('public/js/paginate.js') }}";
        document.head.appendChild(script);

                @if($getclm_activity->count() <= 0)
                toastr.error("Activity record not found!");
                @endif
            }
        }, 50);
    });
    $("#text-set").on('click', function () {
        const buttonText = $(this).text().trim();
    });
});

function task_model(task_id, lead_id, gen_task_id) {
    const buttonText = $("#text-set").text().trim();
    let ajaxUrl="";
    let additionalData = {};
    let additionalDataforTask = {};

    if (buttonText === "Generate Activity") {
         ajaxUrl = "{{ route('activity-tracker') }}";
        additionalData = {
            customField: "Activity Tracker",
            tabPageValue:"activity",
        };
    }else{
        ajaxUrl = "{{ route('clmModelData') }}";
        additionalDataforTask = {
            tabPageValue:"task",
        };
    }
    const requestData = {
        task_id: task_id,
        lead_id: lead_id,
        gen_task_id: gen_task_id,
        ...additionalData,
        ...additionalDataforTask
    };

    $.ajax({
        url: ajaxUrl,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: requestData,
        success: function (response) {
            $("#mdlData").html(response);
            $('#staticBackdrop').modal('show');
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
}

// function task_model(task_id,lead_id,gen_task_id)
//     {
//         $.ajax({
//                 url: "{{ route('clmModelData') }}", 
//                 type: "POST",
//                 headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//                 data:{
//                     task_id:task_id,
//                     lead_id:lead_id,
//                     gen_task_id:gen_task_id,
//                 },
//                 success: function (response) {
//                     $("#mdlData").html(response);
//                     $('#staticBackdrop').modal('show');
//                 },
//                 error: function (xhr) {
//                     console.log(xhr.responseText);
//                 }
//             });
//     } 

    function edit_clm(a) {
        $.ajax({
                url: "{{ url('edit-clm-form') }}", 
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    lead_id:a,
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

    function edit_lead_clm(a) {
        $.ajax({
                url: "{{ url('edit-lead_details') }}", 
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    lead_id:a,
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

    function GetdataByActivityTracker(activityID, forUse,leadID) {
    $.ajax({
        url: "{{ route('reports/activity-tracker-popup-data') }}",
        cache: true,
        type: "post",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            activityID: activityID,
            lead_id:leadID,
            forUse: forUse,
        },
        beforeSend: function() {
            $('#ActivityTrackerModal').modal('hide');
            $('#modalContent').html('');
            //$("#spinner").show();
        },
        success: function(response) {
            if (response) {
                $('#modalContent').html(response);
                if ($('#modalContent').html().trim()) {
                    $('#ActivityTrackerModal').modal('show');
                }
            }
        },
        complete: function() {
            //$('#spinner').hide();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
    $('#ActivityTrackerModal').on('hide.bs.modal', function () {
        $('#modalContent').html('');
    });
}

function EditActitvity(activityID,forUse,leadID) {
    var ajaxUrl = "{{ route('activity-tracker') }}";
    const requestData = {
        activityID: activityID,
        lead_id:leadID,
        usages: 'editActivity',
        tabPageValue:"activity",
    };
    $.ajax({
        url: ajaxUrl,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: requestData,
        success: function (response) {
            console.log(response);
            $("#mdlActivityData").html(response);
            $('#editActivity').modal('show');
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
}
</script>
@if(empty($programInitiationDate))
<script>
    $(document).ready(function () {
        $('#openProgram').modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
    });
</script>
@endif

@endsection
