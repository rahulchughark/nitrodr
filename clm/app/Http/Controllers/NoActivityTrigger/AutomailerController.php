<?php

namespace App\Http\Controllers\NoActivityTrigger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Response;
use Exception;
use Config;
use App\GeneratedTask;
use App\Mail\NoActivityTrigger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutomailerController extends Controller
{

    public function GenerateAutomail(Request $request)
{
    try {
       // $user_email = "gajendra.singh@arkinfo.in";
        $user_email = "bhuban.singh@arkinfo.in";
        //$user_emailcc = "virendra.kumar@arkinfo.in";
        $user_emailcc = "noreply321@arkinfo.in";
        $link = "";
        $msg = "";
        $currentDate = Carbon::now();
        $date30DaysAgo = Carbon::now()->subDays(30)->toDateString();
        $leadsNoActionLast30Days = GeneratedTask::select(
            'generated_task.id',
            'generated_task.lead_id',
            'generated_task.task_generate_date',
            'generated_task.task_due_date',
            'generated_task.task_subject',
            'generated_task.task_owner',
            'generated_task.user_approval_id',
            'u.name as assignName',
            'u1.name as apName'
        )
        ->leftJoin('clm_users as u', 'u.id', '=', 'generated_task.task_owner')
        ->leftJoin('clm_users as u1', 'u1.id', '=', 'generated_task.user_approval_id')
        //->whereDate('generated_task.task_generate_date', '<=', $date30DaysAgo)
        //->where('generated_task.task_status', 'Not Started')
        //->whereNotNull('generated_task.task_owner')
        ->where(function($query) {
            $query->whereNotNull('generated_task.task_owner')
                  ->orWhereNotNull('generated_task.user_approval_id');
        })
        ->groupBy("generated_task.task_owner","generated_task.user_approval_id")
        ->get();
        //dd($leadsNoActionLast30Days);
        $allDataArray = []; // Array to collect all records
        $uniqueAssignNames = []; // Array to track unique assignName
        $lead_ids = $leadsNoActionLast30Days->pluck('id')->toArray();
        if ($leadsNoActionLast30Days->count() > 0) {
           $get_total_task_assign=$get_total_no_activity=$get_total_Completed=0;
            $setname="";
            foreach ($leadsNoActionLast30Days as $key => $leadsData) {
                if(!in_array($leadsData->assignName, $uniqueAssignNames)){
                if($leadsData->user_approval_id=="8" && $leadsData->task_owner==null){

                    $get_total_task_assign = GeneratedTask::where([
                        ["user_approval_id", "=", $leadsData->user_approval_id]
                    ])->whereNull('task_owner')->count();

                    $get_total_no_activity = GeneratedTask::where([
                        ["user_approval_id", "=", $leadsData->user_approval_id],
                        ["task_status", "=", "Not Started"]
                    ])->whereNull('task_owner')->count();

                    $get_total_Completed = GeneratedTask::where([
                        ["user_approval_id", "=", $leadsData->user_approval_id],
                        ["task_status", "=", "Completed"]
                    ])->whereNull('task_owner')->count();


                    $get_total_task_activity = GeneratedTask::where("user_approval_id", $leadsData->user_approval_id)
    ->whereIn("task_status", ["In Progress", "Re-scheduled", "Cancelled"])
    ->whereNull('task_owner')
    ->count();


                    $setname=$leadsData->apName?$leadsData->apName:"N/A";
                }else{
                        $get_total_task_assign=GeneratedTask::where("task_owner",$leadsData->task_owner)->count();
                        $get_total_no_activity=GeneratedTask::where([["task_owner","=",$leadsData->task_owner],["task_status","=","Not Started"]])->count();
                        $get_total_Completed=GeneratedTask::where([["task_owner","=",$leadsData->task_owner],["task_status","=","Completed"]])->count();
                        $get_total_task_activity = GeneratedTask::where("task_owner", $leadsData->task_owner)
                        ->whereIn("task_status", ["In Progress", "Re-scheduled", "Cancelled"])
                        ->count();
                        $setname=$leadsData->assignName?$leadsData->assignName:"N/A";
                }
                $userRecord = DB::table("orders as o")
                    ->select("o.id", "o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email","o.contact","o.school_email", "s.name as state_name", "c.city as city_name")
                    ->leftJoin("cities as c", "o.city", "=", "c.id")
                    ->leftJoin("states as s", "o.state", "=", "s.id")
                    ->where("o.id", $leadsData->lead_id)
                    ->first();
                    $link=url("/no-action-report",["type"=>"no_action_report","lead_id"=>implode(',', $lead_ids)]);
                $dataArray = [
                    "subject" => "CLM-" . $leadsData->task_subject . "-" . $userRecord->school_name . "-" . $userRecord->city_name,
                    "assign_name" => $setname,
                    "total_task_assign"=>$get_total_task_assign,
                    "completed"=>$get_total_Completed,
                    "no_activity"=>$get_total_no_activity,
                    "total_task_activity"=> $get_total_task_activity,
                    "link" => $link,
                ];
                $allDataArray[] = $dataArray; // Collect each record into a single array
                $uniqueAssignNames[] = $leadsData->assignName; // Add assignName to unique list
            }
        }

            if (count($allDataArray) > 0) {
                $emailMessage=$this->EmailSend($allDataArray, $user_email, $user_emailcc);
                $msg = $emailMessage;
            } else {
                $msg = "Something went wrong.!";
            }
        } else {
            $msg = "No data found older than 30 days.";
        }

        echo $msg;
    } catch (\Exception $e) {
        return  $e->getMessage();
        return Response::json(["code" => Config('http-request.MASTER_KEY.Server-Error'), "status" => false, "message" => "Internal Server Error!"])->withHeaders([
            "Content-Type" => "application/json",
            "Accept" => "application/json",
        ]);
    }
}

public function EmailSend($allDataArray, $toEmail, $ccEmail)
{
    try {
        Mail::to($toEmail)
            ->cc($ccEmail)
            ->send(new NoActivityTrigger($allDataArray));
            return "Cron successfully created.!"; // Return success message
    } catch (\Exception $e) {
        return "Failed to send email: " . $e->getMessage(); // Return error message
    }
}
// public function TestEmail(){
//     return view("emails/testemail");
// }

}//end of class
