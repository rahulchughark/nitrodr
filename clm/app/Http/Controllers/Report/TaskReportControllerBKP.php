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
use Carbon\Carbon;
use App\GeneratedTask;
use Validator;
use Response;
use Exception;
use Config;
use App\User;


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
            if(!empty($getID) && !empty($getUserId) && $getToken->count()>0){
             $this->autoLogin($getID,$getUserId,$token);
            }
            else{
                if(\Route::current()->getName()!="/task"){
                    return redirect("/login");
                }
            }
                return view("task", compact('getID','resultUsers','resultSubject','formData','ownerArray','subjectArray'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
    }
    
    public function getTaskReportusingAjax(Request $request){
        try {
            $html="";
                if(AuthHelper::users()->user_type==="FACULTY"){
                    if(!empty($request->CheckID)){
                        $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_generate_date","generated_task.task_due_date","generated_task.task_owner","generated_task.task_subject","generated_task.task_status","o.school_name","generated_task.lead_id")
                        ->leftjoin("orders as o","o.id","=","generated_task.lead_id","cu.name as updated_by")
                        ->leftjoin("clm_users as cu","cu.id","=","generated_task.updated_by")
                        ->where("generated_task.task_owner",AuthHelper::users()->id)
                        ->where("generated_task.id",$request->CheckID)
                        ->whereNotNull("generated_task.task_owner");
                        //->get();
                    }
                    else{
                        $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_generate_date","generated_task.task_due_date","generated_task.task_owner","generated_task.task_subject","generated_task.task_status","o.school_name","generated_task.lead_id","cu.name as updated_by")
                        ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
                        ->leftjoin("clm_users as cu","cu.id","=","generated_task.updated_by")
                        ->where("generated_task.task_owner",AuthHelper::users()->id)
                        ->whereNotNull("generated_task.task_owner");
                        //->get();
                    }
                }
                else{
                    $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_generate_date","generated_task.task_due_date","generated_task.task_owner","generated_task.task_subject","generated_task.task_status","o.school_name","generated_task.lead_id","cu.name as updated_by")
                    ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
                    ->leftjoin("clm_users as cu","cu.id","=","generated_task.updated_by")
                    ->whereNotNull("generated_task.task_owner");
                }
                if(!empty($request->statusValue)){
                    $getAllrecord->where("generated_task.task_status",$request->statusValue);
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
                $Allresult=$getAllrecord->get();
                $totalRecords = count($Allresult);
                if($Allresult->count()>0){
                    foreach ($Allresult as $key => $item) {
                        $name=isset(DB::table('clm_users')->find($item->task_owner)->name)?DB::table('clm_users')->find($item->task_owner)->name:'';
                        $html.="
                        <tr>
                            <td>".++$key."</td>
                            <td style='cursor:pointer' onclick='task_model(".$item->mst_task_id.",".$item->lead_id.",".$item->id.")'>".$item->task_subject." </td>
                            <td><a href='".URL("lead_view/".$item->lead_id)."'>".$item->school_name."</td>
                            <td>".$name."</td>
                            <td >".$item->task_status."</td>
                            <td >".$item->task_generate_date."</td>
                            <td >".$item->task_due_date."</td>
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
           if(AuthHelper::users()->user_type==="FACULTY"){
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
      if(AuthHelper::users()->user_type==="FACULTY"){
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
 if(AuthHelper::users()->user_type==="FACULTY"){
    $getTotalSchool=DB::table("mst_task as m")->select("m.id","m.task as name","gt.mst_task_id","gt.task_status")
    ->leftjoin("generated_task as gt","gt.mst_task_id","=","m.id")
    ->where([["gt.task_status","=",$request->checkStatus],["gt.task_owner","=",AuthHelper::users()->id]])
    ->groupBy("gt.mst_task_id")
    ->get()
    ->map(function ($item, $key) {
       $item->total = DB::table("generated_task")->where("generated_task.mst_task_id", $item->id)->where("generated_task.task_status",$item->task_status)->get();
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
            if(AuthHelper::users()->user_type==="FACULTY"){
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
                                                $html.="
                                                <tr>
                                                                        <td>".++$key."</td>
                                                                        <td>".$item->trainer." </td>
                                                                        <td>".$getTotalSchool."</td>
                                                                        <td>".$getTotalNotStarted."</td>
                                                                        <td>".$getTotalInProgress."</td>
                                                                        <td>".$getTotalCompleted."</td>
                                                                        <td>".$getTotalReScheduled."</td>
                                                                        <td>".$getTotalCancelled."</td>
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
return view('reports/approved-pending-report/pending-approved-reports',$data,compact('resultSubject','formData','subjectArray'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
        
    }

    public function GetTaskForApprovalDataByAjax(Request $request)
    { 
        try {
            $html="";
//             $getAllrecord=GeneratedTask::select("generated_task.id","generated_task.mst_task_id","generated_task.task_subject","generated_task.lead_id","generated_task.task_status","generated_task.task_owner","u.name as ownerName","generated_task.user_approval_id","generated_task.current_status", "generated_task.task_generate_date as created_date","generated_task.task_due_date as due_date","generated_task.task_assigned_date","o.school_name",DB::raw(
//                "
//                CASE
// WHEN generated_task.current_status='1' THEN 'For Approval'
// WHEN generated_task.current_status='0' THEN 'Task Assigned'
//                 END as cStatus
//                "))
//             ->leftjoin("clm_users as u","u.id","=","generated_task.user_approval_id")
//             ->leftjoin("orders as o","o.id","=","generated_task.lead_id")
//             ->where(
//                 function($query){
//                     $query->where([
//                         ["generated_task.current_status","=","1"],
//                         ["u.user_type","=","ADMIN"]
//                     ])
//                     ->orWhere([
//                         ["generated_task.current_status","=","0"],
//                     ])
// ->where([["generated_task.user_approval_id","=","8"]]);
//  });
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
                if($results->count()>0){
                    foreach ($results as $key => $item) {
                         
                                                $html.="
                                                <tr>
                                                                        <td>".++$key."</td>
                                                                        <td>".$item->task_subject." </td>
                                                                        <td>".$item->ownerName."</td>
                                                                        <td>".$item->school_name."</td>
                                                                        <td>".$item->created_date."</td>
                                                                        <td>".$item->due_date."</td>
                                                                        <td>".$text=($item->task_assigned_date?$item->task_assigned_date:"N/A")."</td>
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
                    // "to_email"=>"bhuban.singh@arkinfo.in",//$user_email->email? $user_email->email:"N/A",
                    // "cc_email"=>"virendra.kumar@arkinfo.in",//"imran.desai@ict360.com",
                    "to_email"=>$user_email->email? $user_email->email:"N/A",
                    "cc_email"=>"imran.desai@ict360.com",
                    "link"=>url("/assigned-task",["id"=>$request->mstTaskID,"user_id"=>$user_email->id,"token"=>$token]),
                    ];
                    $this->EmailSend($dataArray);
                }
                else{
                    ?>
                    <script>
                    toastr.error("Somthing went wrong.!");
                   </script>
                    <?php
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
            return $e->getMessage();
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
    $result= Mail::to($dataArray['to_email'])
    ->cc($dataArray['cc_email'])
    ->send(new SendEmail($emailData));
    if($result){
        ?>
        <script>
            alert("Email sent successfully");
        </script>
        <?php
    }else{
        ?>
        <script>
            alert("Email sent failed");
        </script>
        <?php
    }
}
public function autoLogin($taskID,$userID,$token)
    {
        try {
                $getUser=User::find($userID);
                 if($getUser){
                    Auth::login($getUser);
                    $UpdateToken=GeneratedTask::where("token",$token)->update(['token'=>NULL]);
                    return true;
                 }
                else{
                    return redirect('/login');
                }
        } catch (\Throwable $th) {
            return "Something went wrong.!";
        }
       
    }
public function Emailss(){
    return view("emails/testemail");
}

}
