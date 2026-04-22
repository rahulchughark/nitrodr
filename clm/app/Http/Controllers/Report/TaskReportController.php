<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Helpers\AuthHelper;
use App\Helpers\GetAllDataHelpers;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\GeneratedTask;
use Validator;
use Response;
use Exception;
use Config;
use App\User;
use App\Order;


class TaskReportController extends Controller
{
    public function TaskReport(Request $request){
        try {
             if(request('owner')){
                $ownerArray=request('owner');
             }
             else{
                $ownerArray=[];
             }
             if(request('subject')){
                $subjectArray=request('subject');
             }
             else{
                $subjectArray=[];
             }
            $formData=[
                       "createdF_date"=>request('created_from_date'),
                       "createdT_date"=>request('created_to_date'),
                       "F_due_date"=>request('form_due_date'),
                       "T_due_date"=>request('to_due_date'),
                       "owner"=>implode(",",$ownerArray),
                       "subject"=>implode(",",$subjectArray),
                       "status"=>request('status'),
            ];
            $resultUsers=GetAllDataHelpers::Allusers();
            $resultSubject=GetAllDataHelpers::AllSubject();
            $getID=request('task_id');
            $getUserId=request('user_id');
            $token=request('token');
            $getToken=GeneratedTask::where("token",$token)->get();
            if(\Route::current()->getName()=="/renewal-task"){
                $renewalCond = 'Renewal';
            }else{
                $renewalCond = 'Fresh';
            }
            if(!empty($getID) && !empty($getUserId) && $getToken->count()>0){
             $this->autoLogin($getID,$getUserId,$token);
            }
            else{
                if(\Route::current()->getName()!="/task" && \Route::current()->getName()!="/renewal-task"){
                    return redirect("/login");
                }
            }
                return view("task", compact('getID','resultUsers','resultSubject','formData','ownerArray','subjectArray','renewalCond'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
    }
    
    public function getTaskReportusingAjax(Request $request){
        try {
            $html="";
                if(AuthHelper::users()->user_type==="FACULTY" || AuthHelper::users()->user_type==='SALES' || AuthHelper::users()->user_type==='HELPDESK'){
                    if(!empty($request->CheckID)){
                        $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_generate_date","generated_task.task_due_date","generated_task.task_owner","generated_task.task_subject","generated_task.task_status","o.school_name","generated_task.lead_id")
                        ->leftjoin("orders as o","o.id","=","generated_task.lead_id","cu.name as updated_by")
                        ->leftjoin("clm_users as cu","cu.id","=","generated_task.updated_by")
                        ->where("generated_task.task_owner",AuthHelper::users()->id)
                        ->where("generated_task.id",$request->CheckID)
                        ->whereNotNull("generated_task.task_owner");
                    }
                    else{
                        $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_generate_date","generated_task.task_due_date","generated_task.task_owner","generated_task.task_subject","generated_task.task_status","o.school_name","generated_task.lead_id","cu.name as updated_by")
                        ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
                        ->leftjoin("clm_users as cu","cu.id","=","generated_task.updated_by")
                        ->where("generated_task.task_owner",AuthHelper::users()->id)
                        ->whereNotNull("generated_task.task_owner");
                    }
                }else{
                    $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_generate_date","generated_task.task_due_date","generated_task.task_owner","generated_task.task_subject","generated_task.task_status","o.school_name","generated_task.lead_id","cu.name as updated_by")
                    ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
                    ->leftjoin("clm_users as cu","cu.id","=","generated_task.updated_by")
                    ->whereNotNull("generated_task.task_owner");
                }
                if(!empty($request->statusValue)){
                    $getAllrecord->where("generated_task.task_status",$request->statusValue);
                }else{
                    $getAllrecord->whereNotIn("generated_task.task_status", ['Completed', 'Cancelled']);
                }
                 if(!empty($request->created_f_date) && !empty($request->created_t_date)){
                    $startDate = Carbon::parse($request->created_f_date);
                    $endDate = Carbon::parse($request->created_t_date);
                    $getAllrecord->where([["generated_task.task_generate_date",">=",$startDate],["generated_task.task_generate_date","<=",$endDate]]);
                 }
                 if(!empty($request->f_due_date) && !empty($request->t_due_date)){
                    $startDate = Carbon::parse($request->f_due_date);
                    $endDate = Carbon::parse($request->t_due_date);
                    $getAllrecord->where([["generated_task.task_due_date",">=",$startDate],["generated_task.task_due_date","<=",$endDate]]);
                 }
                 if(!empty($request->owner)){
                    $getAllrecord->whereIn("generated_task.task_owner",explode(",", $request->owner));
                 }
                 if(!empty($request->subject)){
                    $getAllrecord->whereIn("generated_task.mst_task_id",explode(",", $request->subject));
                }
                if($request->task_type=='Renewal'){
                     $getAllrecord->where("o.agreement_type",'Renewal');
                     $urlCond = "renewal_lead_view";
                    }else{
                     $getAllrecord->where("o.agreement_type",'Fresh');
                     $urlCond = "lead_view";

                 }
                $Allresult=$getAllrecord->get();
                $totalRecords = count($Allresult);
                if($Allresult->count()>0){
                    foreach ($Allresult as $key => $item) {
                        $name=isset(DB::table('clm_users')->find($item->task_owner)->name)?DB::table('clm_users')->find($item->task_owner)->name:'';
                        $html.="
                        <tr>
                            <td>".++$key."</td>
                            <td style='cursor:pointer' onclick='task_model(".$item->mst_task_id.",".$item->lead_id.",".$item->id.")'>".$item->task_subject." </td>
                            <td><a href='".URL("$urlCond/".$item->lead_id)."'>".$item->school_name."</td>
                            <td>".$name."</td>
                            <td >".$item->task_status."</td>
                            <td >".date("d-m-Y",strtotime($item->task_generate_date))."</td>
                            <td >".date("d-m-Y",strtotime($item->task_due_date))."</td>
                            <td >".$item->updated_by."</td>
                        </tr>
                        ";
                    }
                }
                else{
                    ?>
                    <script>
                     toastr.error("Task record not found!");
                    </script>
                    <?php
                }
                if($totalRecords>0){
                    return response()->json([
                        'html' => $html,
                        'data' => $Allresult,
                        'recordsTotal' => $totalRecords,
                    ]);
                }else{
                    return $html;
                }
        } catch (\Exception $e) {
           return $e->getMessage();
        }
    }

    public function CumulativeReport(Request $request){
        try {
           if(AuthHelper::users()->user_type==='FACULTY' || AuthHelper::users()->user_type==='SALES' || AuthHelper::users()->user_type==='HELPDESK'){
            list($getTotalNotStarted, $getTotalInProgress, $getTotalCompleted, $getTotalReScheduled, $getTotalCancelled) = array_map(function($status){
                return DB::table("generated_task")->where([["task_status", $status],["task_owner",AuthHelper::users()->id]])->count();
            }, ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"]);
           }else{
            list($getTotalNotStarted, $getTotalInProgress, $getTotalCompleted, $getTotalReScheduled, $getTotalCancelled) = array_map(function($status){
                return DB::table("generated_task")->where("task_status", $status)->count();
            }, ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"]);
           }
            $data['not_started']=$getTotalNotStarted;
            $data['in_progress']=$getTotalInProgress;
            $data['completed']= $getTotalCompleted;
            $data['re_sheduled']=$getTotalReScheduled;
            $data['cancelled']=$getTotalCancelled;
          return view("reports/cumulative-report",$data);
        } catch (\Exception $e) {
            return $e->getMessage();
            echo "Something went wrong.!";
        }
    }

    public function FetchCumulativeReportByAjax(Request $request){
        try {
            $html="";
            $rules=[
                "checkStatus"=>"required",
                "forStatus"=>"required",
             ];
             $validators=Validator::make($request->all(), $rules);
             if($validators->fails())
             {
                $failedRules=$validators->getMessageBag()->toArray();
                $errorMsg="";
                if(isset($failedRules['checkStatus']))
                $errorMsg=$failedRules['checkStatus'][0];
             }
             else{
                if($request->forStatus=="School"){
      if(AuthHelper::users()->user_type==="FACULTY" || AuthHelper::users()->user_type==='SALES' || AuthHelper::users()->user_type==='HELPDESK'){
        $getTotalSchool=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.lead_id","generated_task.task_status","o.school_name as name")
        ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
        ->where([["generated_task.task_status","=",$request->checkStatus],["generated_task.task_owner","=",AuthHelper::users()->id]])
        ->groupBy("generated_task.lead_id")
        ->get()
        ->map(function ($item, $key) {
           $item['total'] = DB::table("generated_task")->where([["generated_task.lead_id", $item->lead_id],["generated_task.task_owner","=",AuthHelper::users()->id]])->where("generated_task.task_status",$item->task_status)->get();
           return $item;
       });
      }
      else{
        $getTotalSchool=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.lead_id","generated_task.task_status","o.school_name as name")
        ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
        ->where("generated_task.task_status",$request->checkStatus)
        ->groupBy("generated_task.lead_id")
        ->get()
        ->map(function ($item, $key) {
           $item['total'] = DB::table("generated_task")->where("generated_task.lead_id", $item->lead_id)->where("generated_task.task_status",$item->task_status)->get();
           return $item;
       });
      }
                }
                else if($request->forStatus==="Task"){
 if(AuthHelper::users()->user_type==="FACULTY" || AuthHelper::users()->user_type==='SALES' || AuthHelper::users()->user_type==='HELPDESK'){
    $getTotalSchool=DB::table("mst_task as m")->select("m.id","m.task as name","gt.mst_task_id","gt.task_status")
    ->leftjoin("generated_task as gt","gt.mst_task_id","=","m.id")
    ->where([["gt.task_status","=",$request->checkStatus],["gt.task_owner","=",AuthHelper::users()->id]])
    ->groupBy("gt.mst_task_id")
    ->get()
    ->map(function ($item, $key) {
       $item->total = DB::table("generated_task")->where("generated_task.mst_task_id", $item->id)->where("generated_task.task_status",$item->task_status)->where("generated_task.task_owner",AuthHelper::users()->id)->get();
       return $item;
   });
 }else{
    $getTotalSchool=DB::table("mst_task as m")->select("m.id","m.task as name","gt.mst_task_id","gt.task_status")
    ->leftjoin("generated_task as gt","gt.mst_task_id","=","m.id")
    ->where("gt.task_status",$request->checkStatus)
    ->groupBy("gt.mst_task_id")
    ->get()
    ->map(function ($item, $key) {
       $item->total = DB::table("generated_task")->where("generated_task.mst_task_id", $item->id)->where("generated_task.task_status",$item->task_status)->get();
       return $item;
   });
 }
                }
                if($getTotalSchool->count()>0){
                    foreach ($getTotalSchool as $keyy => $res) {
                        $html.="
                        <tr>
                                                <td>".++$keyy."</td>
                                                <td>".$res->name."</td>
                                                <td>".$res->total->count()."</td>
                                            </tr>
                        ";
                    }
                }
                else{
                   $html.="error";
                }
                return $html;
             }
        } catch (\Exception $e) {
            
           return "Something went wrong.!";
        }
    }
    public function TrackerTrainerWiseReport()
    {
        try {
             if(request('trainer')){
                $trainer=request('trainer');
             }
             else{
                $trainer=[];
             }
             $trainerData=[
               "traninerName"=>implode(",",$trainer),
             ];
            $resultUsers=GetAllDataHelpers::Allusers();
            return view('reports/trainer-report',compact('resultUsers','trainerData','trainer'));
        } catch (\Exception $e) {
            return "Something went wrong.!";
        }
        
    }
    public function GetTrainerWiseDataByAjax(Request $request)
    { 
        try {
            $html="";
            if(AuthHelper::users()->user_type==='FACULTY' || AuthHelper::users()->user_type==='SALES' || AuthHelper::users()->user_type==='HELPDESK'){
                $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.lead_id","generated_task.task_status","generated_task.task_owner","u.name as trainer")
                ->leftjoin("clm_users as u","u.id","=","generated_task.task_owner")
                ->where("generated_task.task_owner",AuthHelper::users()->id);
            }
            else{
                $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.lead_id","generated_task.task_status","generated_task.task_owner","u.name as trainer")
                ->leftjoin("clm_users as u","u.id","=","generated_task.task_owner");
            }
             if(!empty($request->trainer)){
                $getAllrecord->whereIn("generated_task.task_owner",explode(",",$request->trainer));
             }
             $trainerData=$getAllrecord->whereNotNull("generated_task.task_owner")->groupBy("generated_task.task_owner")->get();
                if($trainerData->count()>0){
                    foreach ($trainerData as $key => $item) {
                        list($getTotalNotStarted, $getTotalInProgress, $getTotalCompleted, $getTotalReScheduled, $getTotalCancelled) = array_map(function($status) use ($item) {
                                                    return DB::table("generated_task")->where("generated_task.task_owner", $item->task_owner)->where("task_status", $status)->count();
                                                }, ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"]);
                                              $getTotalSchool=DB::table("generated_task")->where([["generated_task.task_owner","=",$item->task_owner]])->groupBy("generated_task.lead_id")->get()->count();
                                                $getTotalSchoolTask=DB::table("generated_task")->where([["generated_task.task_owner","=",$item->task_owner]])->count();
                                                $html .= "
                                                <tr>
                                                    <td>" . ++$key . "</td>
                                                    <td>" . htmlspecialchars($item->trainer) . "</td>

                                                    <td style='text-align:center'>
                                                 <a href='".($getTotalSchool > 0 ?URL("reports/all-assign-task-schools?&string=".Crypt::encrypt($item->task_owner)."&assign=".Crypt::encrypt('trainer-assign')." ") :"javavoidpoint(0)")."' style='cursor:".($getTotalSchool > 0 ?'pointer' :'default')." ' target=".($getTotalSchool > 0 ?'_blank' :'').">". ($getTotalSchool ?? 0)."</a>
                                                    </td>


                                                    <td style='text-align:center'>
                                                        <span style='cursor:" . ($getTotalSchoolTask > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($getTotalSchoolTask > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($getTotalSchoolTask > 0 ? "GetdataByTaskStatus(" . $item->task_owner . ", \"Schools\", \"trainer\")" : "return false;") . "'>
                                                            " . $getTotalSchoolTask . "
                                                        </span>
                                                    </td>
                                                    <td style='text-align:center'>
                                                        <span style='cursor:" . ($getTotalNotStarted > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($getTotalNotStarted > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($getTotalNotStarted > 0 ? "GetdataByTaskStatus(" . $item->task_owner . ", \"Not Started\", \"trainer\")" : "return false;") . "'>
                                                            " . $getTotalNotStarted . "
                                                        </span>
                                                    </td>
                                                    <td style='text-align:center'>
                                                        <span style='cursor:" . ($getTotalInProgress > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($getTotalInProgress > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($getTotalInProgress > 0 ? "GetdataByTaskStatus(" . $item->task_owner . ", \"In Progress\", \"trainer\")" : "return false;") . "'>
                                                            " . $getTotalInProgress . "
                                                        </span>
                                                    </td>
                                                    <td style='text-align:center'> 
                                                        <span style='cursor:" . ($getTotalCompleted > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($getTotalCompleted > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($getTotalCompleted > 0 ? "GetdataByTaskStatus(" . $item->task_owner . ", \"Completed\", \"trainer\")" : "return false;") . "'>
                                                            " . $getTotalCompleted . "
                                                        </span>
                                                    </td>
                                                    <td style='text-align:center'>
                                                        <span style='cursor:" . ($getTotalReScheduled > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($getTotalReScheduled > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($getTotalReScheduled > 0 ? "GetdataByTaskStatus(" . $item->task_owner . ", \"Re-scheduled\", \"trainer\")" : "return false;") . "'>
                                                            " . $getTotalReScheduled . "
                                                        </span>
                                                    </td>
                                                    <td style='text-align:center'>
                                                        <span style='cursor:" . ($getTotalCancelled > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($getTotalCancelled > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($getTotalCancelled > 0 ? "GetdataByTaskStatus(" . $item->task_owner . ", \"Cancelled\", \"trainer\")" : "return false;") . "'>
                                                            " . $getTotalCancelled . "
                                                        </span>
                                                    </td> 
                                                </tr>
                                                ";

                                            }
                }
                else{
                   ?>
                   <script>
                    toastr.error("Task record not found!");
                   </script>
                   <?php
                }
                return $html;
        } catch (\Exception $e) {
            ?>
                   <script>
                    toastr.error("Something went wrong.!");
                   </script>
                   <?php
        }
    }
    public function PendingAndApprovalReport($type=null)
    {
        try {
            $resultSubject=GetAllDataHelpers::AllSubject();
             // condition for no action report
            if(request("type")==="no_action_report"){
                $ids = explode(',',  request("task_id"));
                if(request('subject')){
                    $subjectArray=request('subject');
                 }
                 else{
                    $subjectArray=[];
                 }
                $formData=[
                    "pageName"=>'automailerSection',
                    "task_no_action"=>request("task_id"),
                    "createdF_date"=>request('created_from_date'),
                    "createdT_date"=>request('created_to_date'),
                    "F_due_date"=>request('form_due_date'),
                    "T_due_date"=>request('to_due_date'),
                    "subject"=>implode(",",$subjectArray),
         ];
         $userType="";
         $getUserId=13;
         $ForUse="AutoLoginFrofCEO";
         $this->autoLogin($userType, $getUserId,$ForUse);
            }else{
                $data=[];
                $data['getID']=request('id');
                $getCurrentURL= \Route::current()->getName();
                 if(request('subject')){
                    $subjectArray=request('subject');
                 }
                 else{
                    $subjectArray=[];
                 }
                $formData=[
                           "createdF_date"=>request('created_from_date'),
                           "createdT_date"=>request('created_to_date'),
                           "F_due_date"=>request('form_due_date'),
                           "T_due_date"=>request('to_due_date'),
                           "subject"=>implode(",",$subjectArray),
                ];
     if($getCurrentURL==="/approved/reports"){
      $data["title"]="Approved Task Reports";
     }
     else if($getCurrentURL==="/task-for-approval"){
        $data["title"]="Task for Approval";
     }
                $getUserId=request('user_id');
                $token=request('token');
                $getToken=GeneratedTask::where("token",$token)->get();
                if(!empty($data['getID']) && !empty($getUserId) && $getToken->count()>0){
                    $this->autoLogin($data['getID'],$getUserId,$token);
                   }
                   else{
                       if(\Route::current()->getName()!="/task-for-approval"){
                           return redirect("/login");
                       }
                   }
            }
            // condition for send data no action report
            if(request("type")==="no_action_report"){
                return view('reports/approved-pending-report/pending-approved-reports',compact('resultSubject','formData','subjectArray'));
            }else{
                return view('reports/approved-pending-report/pending-approved-reports',$data,compact('resultSubject','formData','subjectArray'));
            }
           
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
        
    }

    public function GetTaskForApprovalDataByAjax(Request $request)
    { 
        try {
            $leadsNoActionLast30Days=array();
            $html="";
$getAllrecord = GeneratedTask::select(
    "generated_task.id",
    "generated_task.mst_task_id",
    "generated_task.task_subject",
    "generated_task.lead_id",
    "generated_task.task_status",
    "generated_task.task_owner",
    DB::raw("COALESCE(owner_user.name, approver_user.name) as ownerName"),
    "generated_task.user_approval_id",
    "generated_task.current_status",
    "generated_task.task_generate_date as created_date",
    "generated_task.task_due_date as due_date",
    "generated_task.task_assigned_date",
    "o.school_name",
    DB::raw("
        CASE
            WHEN generated_task.current_status='1' THEN 'For Approval'
            WHEN generated_task.current_status='0' THEN 'Task Assigned'
        END as cStatus
    ")
)
->leftJoin("clm_users as owner_user", "owner_user.id", "=", "generated_task.task_owner")
->leftJoin("clm_users as approver_user", "approver_user.id", "=", "generated_task.user_approval_id")
->leftJoin("orders as o", "o.id", "=", "generated_task.lead_id")
->where(function($query) {
    $query->where([
        ["generated_task.current_status", "=", "1"],
        ["approver_user.user_type", "=", "ADMIN"]
    ])
    ->orWhere("generated_task.current_status", "=", "0")
    ->where("generated_task.user_approval_id", "=", "8");
});
            if(!empty($request->created_f_date) && !empty($request->created_t_date) && $request->pageSection!=="automailerSection"){
                $startDate = Carbon::parse($request->created_f_date);
                $endDate = Carbon::parse($request->created_t_date);
                $getAllrecord->where([["generated_task.task_generate_date",">=",$startDate],["generated_task.task_generate_date","<=",$endDate]]);
             }
             if(!empty($request->f_due_date) && !empty($request->t_due_date) && $request->pageSection!=="automailerSection"){
                $startDate = Carbon::parse($request->f_due_date);
                $endDate = Carbon::parse($request->t_due_date);
                $getAllrecord->where([["generated_task.task_due_date",">=",$startDate],["generated_task.task_due_date","<=",$endDate]]);
             }
             if(!empty($request->subject) && $request->pageSection!=="automailerSection"){
                $getAllrecord->whereIn("generated_task.mst_task_id",explode(",", $request->subject));
             }

             /// add new condition for automailer
             if($request->pageSection==="automailerSection"){
                $ids = explode(',',  request("taskID"));
                $getAllrecord = GeneratedTask::select(
                    'generated_task.id', 
                    'generated_task.lead_id', 
                    'generated_task.task_generate_date', 
                    'generated_task.task_due_date', 
                    'generated_task.task_subject',
                    'u.name as assignName',
                    'u1.name as apName',
                    'generated_task.task_owner',
                    'generated_task.user_approval_id',
                )
                ->leftJoin('clm_users as u', 'u.id', '=', 'generated_task.task_owner')
                ->leftJoin('clm_users as u1', 'u1.id', '=', 'generated_task.user_approval_id')
                ->whereIn('generated_task.id', $ids)
                ->groupBy("generated_task.task_owner");
                if(!empty($request->created_f_date) && !empty($request->created_t_date)){
                    $startDate = Carbon::parse($request->created_f_date);
                    $endDate = Carbon::parse($request->created_t_date);
                    $getAllrecord->where([["generated_task.task_generate_date",">=",$startDate],["generated_task.task_generate_date","<=",$endDate]]);
                 }
                 if(!empty($request->f_due_date) && !empty($request->t_due_date)){
                    $startDate = Carbon::parse($request->f_due_date);
                    $endDate = Carbon::parse($request->t_due_date);
                    $getAllrecord->where([["generated_task.task_due_date",">=",$startDate],["generated_task.task_due_date","<=",$endDate]]);
                 }
                 if(!empty($request->subject)){
                    $getAllrecord->whereIn("generated_task.mst_task_id",explode(",", $request->subject));
                 }    
                $results=$getAllrecord->get();
             }else{
                $results=$getAllrecord->get();
             }
                if($results->count()>0){
                    $count=0;
                    $uniqueAssignNames = [];
                    foreach ($results as $key => $item) {
                        if($request->pageSection==="automailerSection"){
                            if(!in_array($item->assignName, $uniqueAssignNames)){
                                if($item->user_approval_id=="8" && $item->task_owner==null){
                                    $get_total_task_assign = GeneratedTask::where([
                                        ["user_approval_id", "=", $item->user_approval_id]
                                    ])->whereNull('task_owner')->count();
                                    $taskCounts = GeneratedTask::where('user_approval_id', $item->user_approval_id)
    ->whereNull('task_owner')
    ->selectRaw("task_status, COUNT(*) as count")
    ->groupBy('task_status')
    ->pluck('count', 'task_status');
$get_total_no_activity = $taskCounts['Not Started'] ?? 0;
$get_total_In_Progress_activity = $taskCounts['In Progress'] ?? 0;
$get_total_completed_activity = $taskCounts['Completed'] ?? 0;
$get_total_Re_scheduled_activity = $taskCounts['Re-scheduled'] ?? 0;
$get_total_Cancelled_activity = $taskCounts['Cancelled'] ?? 0;
                                    $setname=$item->apName?$item->apName:"N/A";
                                    $user_id=$item->user_approval_id;
                                }else{
                                        $get_total_task_assign=GeneratedTask::where("task_owner",$item->task_owner)->count();
                                        $taskCounts = GeneratedTask::where('task_owner', $item->task_owner)
    ->whereIn('task_status', ['Not Started', 'In Progress', 'Completed', 'Re-scheduled', 'Cancelled'])
    ->selectRaw('task_status, COUNT(*) as count')
    ->groupBy('task_status')
    ->pluck('count', 'task_status');
$get_total_no_activity = $taskCounts['Not Started'] ?? 0;
$get_total_In_Progress_activity = $taskCounts['In Progress'] ?? 0;
$get_total_completed_activity = $taskCounts['Completed'] ?? 0;
$get_total_Re_scheduled_activity = $taskCounts['Re-scheduled'] ?? 0;
$get_total_Cancelled_activity = $taskCounts['Cancelled'] ?? 0;
                                        $setname=$item->assignName?$item->assignName:"N/A";
                                        $user_id=$item->task_owner;
                                }
                            }
                            $html.="
                            <tr>
                                                     <td>".++$key."</td>

                                                     <td>". $setname."</td>

                                                     <td style='text-align:centerrrr'>
                                                        <span style='cursor:" . ( $get_total_task_assign > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ( $get_total_task_assign > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ( $get_total_task_assign > 0 ? "GetdataByTaskStatus(" . $user_id . ", \"Task Assigned\", \"no-action-report\")" : "return false;") . "'>
                                                            " . ( $get_total_task_assign) . "
                                                        </span>
                                                    </td>
                                                    <td style='text-align:centerrrr'>
                                                        <span style='cursor:" . ($get_total_no_activity > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($get_total_no_activity > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($get_total_no_activity > 0 ? "GetdataByTaskStatus(" . $user_id . ", \"Not Started\", \"no-action-report\")" : "return false;") . "'>
                                                            " . $get_total_no_activity . "
                                                        </span>
                                                    </td>
                                                    
                                                     <td style='text-align:centerrrr'>
                                                        <span style='cursor:" . ($get_total_In_Progress_activity > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($get_total_In_Progress_activity > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($get_total_In_Progress_activity > 0 ? "GetdataByTaskStatus(" . $user_id . ", \"In Progress\", \"no-action-report\")" : "return false;") . "'>
                                                            " . $get_total_In_Progress_activity . "
                                                        </span>
                                                    </td>

                                                     <td style='text-align:centerrrr'>
                                                        <span style='cursor:" . ($get_total_completed_activity  > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($get_total_completed_activity  > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($get_total_completed_activity  > 0 ? "GetdataByTaskStatus(" . $user_id . ", \"Completed\", \"no-action-report\")" : "return false;") . "'>
                                                            " . $get_total_completed_activity  . "
                                                        </span>
                                                    </td>

                                                     <td style='text-align:centerrrr'>
                                                        <span style='cursor:" . ($get_total_Re_scheduled_activity  > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($get_total_Re_scheduled_activity  > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($get_total_Re_scheduled_activity  > 0 ? "GetdataByTaskStatus(" . $user_id . ", \"Re-scheduled\", \"no-action-report\")" : "return false;") . "'>
                                                            " . $get_total_Re_scheduled_activity  . "
                                                        </span>
                                                    </td>

                                                     <td style='text-align:centerrrr'>
                                                        <span style='cursor:" . ($get_total_Cancelled_activity  > 0 ? 'pointer' : 'default') . "' 
                                                              data-bs-toggle='modal' 
                                                              data-bs-target='" . ($get_total_Cancelled_activity  > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                                              onclick='" . ($get_total_Cancelled_activity  > 0 ? "GetdataByTaskStatus(" . $user_id . ", \"Cancelled\", \"no-action-report\")" : "return false;") . "'>
                                                            " . $get_total_Cancelled_activity  . "
                                                        </span>
                                                    </td>";
                                                    $html.="</tr>";
                        }else{
                            $html.="
                            <tr>
                                                    <td>".++$key."</td>
                                                    <td>".$item->task_subject." </td>
                                                     <td>".$item->school_name."</td>
                                                    <td>".$item->ownerName."</td>
                                                    <td>".date("d-m-Y",strtotime($item->created_date))."</td>
                                                    <td>".date("d-m-Y",strtotime($item->due_date))."</td>
                                                    <td>".$text=($item->task_assigned_date?date("d-m-Y",strtotime($item->task_assigned_date)):"N/A")."</td>
                                                    <td>".$item->cStatus."</td>";
                                                    if($item->current_status == 1){
                                                        $html .= "
                                                        <td style='cursor:pointer' onclick='task_model(".$item->user_approval_id.", ".$item->id.")'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'>
                                                            <circle cx='17.99' cy='10.36' r='6.81' fill='currentColor' class='clr-i-solid clr-i-solid-path-1'/>
                                                            <path fill='currentColor' d='M12 26.65a2.8 2.8 0 0 1 4.85-1.8L20.71 29l6.84-7.63A16.81 16.81 0 0 0 18 18.55A16.13 16.13 0 0 0 5.5 24a1 1 0 0 0-.2.61V30a2 2 0 0 0 1.94 2h8.57l-3.07-3.3a2.81 2.81 0 0 1-.74-2.05' class='clr-i-solid clr-i-solid-path-2'/>
                                                            <path fill='currentColor' d='M28.76 32a2 2 0 0 0 1.94-2v-3.76L25.57 32Z' class='clr-i-solid clr-i-solid-path-3'/>
                                                            <path fill='currentColor' d='M33.77 18.62a1 1 0 0 0-1.42.08l-11.62 13l-5.2-5.59a1 1 0 0 0-1.41-.11a1 1 0 0 0 0 1.42l6.68 7.2L33.84 20a1 1 0 0 0-.07-1.38' class='clr-i-solid clr-i-solid-path-4'/>
                                                            <path fill='none' d='M0 0h36v36H0z'/>
                                                        </svg>
                                                        </td>";
                                                    }
                                                    else if($item->current_status == 0){
                                                        $html.="<td style='color:red'><strong>Assigned</strong></td>";
                                                    }
                                                    $html.="</tr>";
                        }
                        $uniqueAssignNames[] = $item->assignName;
                                            }
                }
                else{
                   ?>
                   <script>
                    toastr.error("Record not found!");
                   </script>
                   <?php
                }
                return $html;
        } catch (\Exception $e) {
            ?>
                   <script>
                    toastr.error("Something went wrong.!");
                   </script>
                   <?php
        }
    }

    public function GetAllClmUsers(Request $request){
        try {
             if(!empty($request->userID) && !empty($request->approvalId) && !empty($request->mstTaskID))
             {
                $getDataForUpdate=GeneratedTask::find($request->mstTaskID);
                if(!empty($getDataForUpdate)){
                    $token= Str::random(60);
                    $getDataForUpdate->task_owner=$request->userID;
                    $getDataForUpdate->current_status=0;
                    $getDataForUpdate->	task_assigned_date=date('Y-m-d');
                    $getDataForUpdate->token=$token;
                    $getDataForUpdate->save();
                    ?>
                    <script>
                    toastr.success("Task Successful Assigned.!");
                   </script>
                    <?php

$userRecord = DB::table("orders as o")
->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email","s.name as state_name","c.city as city_name")
->leftJoin("cities as c", "o.city", "=", "c.id")
->leftJoin("states as s", "o.state", "=", "s.id")
->where("o.id", $getDataForUpdate->lead_id)
->first();

                   $user_email=DB::table("clm_users")
                   ->select("id","email")
                   ->where("id",$getDataForUpdate->task_owner)->first();
                    $dataArray=[
                    "subject"=>"CLM-".$getDataForUpdate->task_subject."-".$userRecord->school_name."-".$userRecord->city_name,
                    "generated_date"=>$getDataForUpdate->task_generate_date,
                    "due_date"=>$getDataForUpdate->task_due_date,
                    "task_subject"=>$getDataForUpdate->task_subject,
                    "school_name"=>$userRecord->school_name?$userRecord->school_name:"N/A",
                    "address"=>$userRecord->address?$userRecord->address:"N/A",
                    "city"=>$userRecord->city_name?$userRecord->city_name:"N/A",
                    "state"=>$userRecord->state_name?$userRecord->state_name:"N/A",
                    "Contact_number"=>$userRecord->eu_mobile?$userRecord->eu_mobile:"N/A",
                    "email_id"=>$userRecord->eu_email?$userRecord->eu_email:"N/A",
                    "to_email"=>"bhuban.singh@arkinfo.in",
                    "cc_email"=>"noreplyvirendra.kumar@arkinfo.in",
                   // "to_email"=>$user_email->email? $user_email->email:"N/A",
                    //"cc_email"=>"imran.desai@ict360.com",
                    "link"=>url("/assigned-task",["id"=>$request->mstTaskID,"user_id"=>$user_email->id,"token"=>$token]),
                    ];
                    $chekMailStatus=$this->EmailSend($dataArray);
                    if ($chekMailStatus == 1) {
                        session()->flash('success', 'Task assigned and email sent successfully.');
                        session()->flash('alert-type', 'success');
                    } else {
                        session()->flash('success', 'Task assigned but email failed to send.');
                        session()->flash('alert-type', 'danger');
                    }
                }
                else{
                    session()->flash('success', 'Something went wrong while updating the task.');
                    session()->flash('alert-type', 'danger');
                }
                return redirect()->back();
             }
            else{
                $getClmUser=DB::table("clm_users as u")->select("u.id","u.name","u.user_type")->whereIn('user_type',['FACULTY','HELPDESK'])->orWhere('id',8)->get();
                if($getClmUser->count()>0){
                  return response()->json($getClmUser);
                }
            }
        } catch (\Exception $e) {
           return "Something went wrong.!";
        }
    }

public function EmailSend($dataArray)
{
    $emailData = [
        "subject"=>$dataArray['subject'],
        "generated_date"=>$dataArray['generated_date'],
                    "due_date"=>$dataArray['due_date'],
                    "task_subject"=>$dataArray['task_subject'],
                    "school_name"=>$dataArray['school_name'],
                    "address"=>$dataArray['address'],
                    "city"=>$dataArray['city'],
                    "state"=>$dataArray['state'],
                    "Contact_number"=>$dataArray['Contact_number'],
                    "email_id"=>$dataArray['email_id'],
                    "link"=>$dataArray['link']

    ];
    try {
        Mail::to($dataArray['to_email'])
            ->cc($dataArray['cc_email'])
            ->send(new SendEmail($emailData));
        return 1;
    } catch (\Exception $e) {
        Log::error('Email sending failed: ' . $e->getMessage());
        return 0;
    }
}
public function autoLogin($taskID,$userID,$token)
    {
        try {
                $getUser=User::find($userID);
                 if($getUser){
                    Auth::login($getUser);
                    if($token!=="AutoLoginFrofCEO"){
                        $UpdateToken=GeneratedTask::where("token",$token)->update(['token'=>NULL]);
                    }
                    return true;
                 }
                else{
                    return redirect('/login');
                }
        } catch (\Throwable $th) {
            return "Something went wrong.!";
        }
       
    }

    public function GetTrainerCountDataByAjax(Request $request){
        try {
            $html="";
            if(!empty($request->mstID) && !empty($request->status)){
                if($request->forpage==="trainer" || $request->forpage==="no-action-report"){
                    if($request->forpage==="no-action-report" && $request->mstID==="8" && $request->status=="Not Started"){
                        $getSchoolData = Order::leftJoin("generated_task as gt", "orders.id", "=", "gt.lead_id")
                        ->leftJoin("city as c", "orders.city", "=", "c.id")
                        ->select(
                            DB::raw("COALESCE(orders.school_name, 'N/A') as school_name"),
                            DB::raw("COALESCE(orders.contact, 'N/A') as contact"),
                            DB::raw("COALESCE(orders.school_email, 'N/A') as school_email"),
                            DB::raw("COALESCE(c.name, 'N/A') as city"),
                            DB::raw("COALESCE(gt.task_subject, 'N/A') as taskSubject"),
                            DB::raw("COALESCE(orders.pincode, 'N/A') as pincode"),
                            "gt.lead_id"
                        );
                    }else{
                        $getSchoolData = Order::leftJoin("generated_task as gt", "orders.id", "=", "gt.lead_id")
                        ->leftJoin("city as c", "orders.city", "=", "c.id")
                        ->select(
                            DB::raw("COALESCE(orders.school_name, 'N/A') as school_name"),
                            DB::raw("COALESCE(orders.contact, 'N/A') as contact"),
                            DB::raw("COALESCE(orders.school_email, 'N/A') as school_email"),
                            DB::raw("COALESCE(c.name, 'N/A') as city"),
                            DB::raw("COALESCE(gt.task_subject, 'N/A') as taskSubject"),
                            DB::raw("COALESCE(orders.pincode, 'N/A') as pincode"),
                            "gt.lead_id"
                        );
                    }
                }
                else if($request->forpage==="cumulative"){
                    $getSchoolData = Order::leftJoin("generated_task as gt", "orders.id", "=", "gt.lead_id")
                    ->leftJoin("city as c", "orders.city", "=", "c.id")
                    ->select(
                        DB::raw("COALESCE(orders.school_name, 'N/A') as school_name"),
                        DB::raw("COALESCE(orders.contact, 'N/A') as contact"),
                        DB::raw("COALESCE(orders.school_email, 'N/A') as school_email"),
                        DB::raw("COALESCE(c.name, 'N/A') as city"),
                        DB::raw("COALESCE(gt.task_subject, 'N/A') as taskSubject"),
                        DB::raw("COALESCE(orders.pincode, 'N/A') as pincode"),
                        "gt.lead_id"
                    );
                    if(AuthHelper::users()->user_type!=="ADMIN"){
                        $getSchoolData->where("gt.task_owner",AuthHelper::users()->id);
                    }
                }
                $validStatuses = ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"];
                if (in_array($request->status, $validStatuses)) {
                    $getSchoolData->where("gt.task_status", $request->status);
                }
                if ($request->mstID =="8" && $request->forpage==="no-action-report") {
                    if($request->status=="Not Started" && $request->mstID=="8"){
                        $getSchoolData->where(function ($query) {
                            $query->where('gt.user_approval_id', 8)
                                  ->WhereNull('gt.task_owner');
                        });
                    }else{
                        $getSchoolData->orWhere(function($query) {
                            $query->where("gt.user_approval_id", 8)
                                  ->WhereNull("gt.task_owner");
                        });
                    }
                } 
                else{
                    if($request->forpage!="cumulative")
                    $getSchoolData->where("gt.task_owner", $request->mstID);
                }
               
                $results = $getSchoolData->get();
                if($results->count()>0){
                    foreach( $results as $key => $item){
                        $html.="
                        <tr>
                                                <td>".++$key."</td>
                                                 <td>".$item->taskSubject."</td>
                                                <td style='text-align:center'><a href='".URL("lead_view/".$item->lead_id)."' target='_blank'>". $item->school_name."</a></td>
                                                <td style='text-align:center'>". $item->contact."</td>
                                                <td style='text-align:center'>". $item->school_email."</td>
                                                <td style='text-align:center'>". $item->city."</td>
                                                <td style='text-align:center'>". $item->pincode."</td>
                                            </tr>
                        ";
                    }
                    }
                    return $html;
            }
        } catch (\Exception $th) {
           return "Something went  wrong.!";
        }
    }

    public function ActivityTrackerReports(Request $request){
        try {
            $resultUsers=GetAllDataHelpers::Allusers();
            if($resultUsers->count()>0){
                $data['users']=$resultUsers;
            }else{
                $data['users']=[];
            }
            $formData=[
                "createdF_date"=>request('created_from_date'),
                "createdT_date"=>request('created_to_date'),
                "F_due_date"=>request('form_due_date'),
                "T_due_date"=>request('to_due_date'),
                "partner_name"=>request('partner_name'),
     ];
            $data['title']="Tracker-Activity";
          return view("reports/reports/activity-tracker",$data, $formData);
        } catch (\Exception $e) {
            return $e->getMessage();
            echo "Something went wrong.!";
        }
    }
    public function GetTrackerActivityWiseData(Request $request)
    { 
        try {
            $html="";
                $getAllrecord=DB::table("clm_activity as activity_clm")->select("activity_clm.id", "activity_clm.created_by", "activity_clm.lead_id","activity_clm.status","o.school_name")
                ->leftjoin("orders as o","o.id","=","activity_clm.lead_id");
                if(AuthHelper::users()->user_type!=="ADMIN"){
                    $getAllrecord->where("activity_clm.created_by",AuthHelper::users()->id);
                }
                //// filter section /////
                if(!empty($request->created_f_date) && !empty($request->created_t_date)){
                    $startDate = Carbon::parse($request->created_f_date);
                    $endDate = Carbon::parse($request->created_t_date);
                    $getAllrecord->where([["activity_clm.created_at",">=",$startDate],["activity_clm.created_at","<=",$endDate]]);
                 }
                 if(!empty($request->f_due_date) && !empty($request->t_due_date)){
                    $startDate = Carbon::parse($request->f_due_date);
                    $endDate = Carbon::parse($request->t_due_date);
                    $getAllrecord->where([["activity_clm.follow_up_date",">=",$startDate],["activity_clm.follow_up_date","<=",$endDate]]);
                 }
                 if(!empty($request->partner_name)){
                    $getAllrecord->where("activity_clm.created_by",$request->partner_name);
                 }
                /// end filter section ////
                $Allactivity=$getAllrecord->groupBy("activity_clm.lead_id")->get();
                if($Allactivity->count()>0){
                    foreach ($Allactivity as $key => $item) {
                        if(AuthHelper::users()->user_type!=="ADMIN"){
                            $getTotalActivity=DB::table("clm_activity")->where([["lead_id","=",$item->lead_id],["created_by","=",AuthHelper::users()->id]])->count();
                            list($getTotalclosed, $getTotalopen) = array_map(function($status) use ($item) {
                                return DB::table("clm_activity")->where("lead_id","=",$item->lead_id)->where("created_by",AuthHelper::users()->id)->where("status", $status)->count();
                               }, [0, 1]);  
                        }else{
                            list($getTotalclosed, $getTotalopen) = array_map(function($status) use ($item) {
                                return DB::table("clm_activity")->where("lead_id","=",$item->lead_id)->where("status", $status)->count();
                            }, [0, 1]);
                            $getTotalActivity=DB::table("clm_activity")->where([["lead_id","=",$item->lead_id]])->count();
                        }
                        $html .= "
<tr>
    <td>" . ++$key . "</td>
    <td>" . htmlspecialchars($item->school_name) . "</td>
    <td>";
if ($getTotalActivity > 0) {
    $html .= "<a href='" . url("lead_view/$item->lead_id?&tab=activity&flag=activites&checktrue=condition") . "'>" . htmlspecialchars($getTotalActivity) . "</a>";
} else {
    $html .= htmlspecialchars($getTotalActivity);
}
$html .= "</td>
    <td>";
if ($getTotalclosed > 0) {
    $html .= "<a href='" . url("lead_view/$item->lead_id?&tab=activity&flag=closed&checktrue=condition") . "'>" . htmlspecialchars($getTotalclosed) . "</a>";
} else {
    $html .= htmlspecialchars($getTotalclosed);
}
$html .= "</td>
    <td>";
if ($getTotalopen > 0) {
    $html .= "<a href='" . url("lead_view/$item->lead_id?&tab=activity&flag=open&checktrue=condition") . "'>" . htmlspecialchars($getTotalopen) . "</a>";
} else {
    $html .= htmlspecialchars($getTotalopen);
}
$html .= "</td>
</tr>";


                        }
                }
                else{
                   ?>
                   <script>
                    toastr.error("Activity record not found!");
                   </script>
                   <?php
                }
                return $html;
        } catch (\Exception $e) {
            return $e->getMessage();
           return "Something went wrong.!";
        }
    }
public function Emailss(){
    return view("emails/testemail");
}

}
