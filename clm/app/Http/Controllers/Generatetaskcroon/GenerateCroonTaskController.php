<?php
namespace App\Http\Controllers\Generatetaskcroon;

use App\GeneratedTask;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use Carbon\Carbon;
use Config;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Response;

class GenerateCroonTaskController extends Controller
{
    public function GenerateTask(Request $request)
    {
        try {
            $msg     = $daycount     = $task_gen     = "";
            $userId  = null;
            $ownerId = null;
            $link    = "";
            $message = "";
            $leads   = DB::table("orders")
                ->select("id", "stage", "add_comm", "program_initiation_date", "latest_task_inserted", "faculty_id", "data_ref")
                ->whereNotNull('program_initiation_date')
                ->where('agreement_type', 'Fresh')
                ->where(function ($query) {
                    $query->where('latest_task_inserted', '<', 21)
                        ->orWhereNull('latest_task_inserted');
                })->get();
            //dd($leads);
            if ($leads->count() > 0) {
                foreach ($leads as $key => $lead) {

                    $today = Carbon::today();

                    $programDate = Carbon::now();
                    if ($lead->program_initiation_date <= $programDate) {
                        $daycount = $programDate->diffInDays($lead->program_initiation_date);
                    } else {
                        $daycount = 0;
                    }

                    //dd($daycount);
                    $excludedTasks = [7, 9];
                    
                    $getTask = DB::table("mst_task")
                        ->select("id", "task", "day", "task_gen", "type")
                        ->where("day", "<=", $daycount)
                        ->whereNotNull("day")
                        ->whereNotIn("id", $excludedTasks)
                        ->get();
                    if ($getTask->count() > 0) {
                        $token = Str::random(60);
                        foreach ($getTask as $key2 => $finalValue) {
                            $CheckMstBeforeInsert = GeneratedTask::where([["mst_task_id", "=", $finalValue->id], ["lead_id", "=", $lead->id]])->first();
                            $insertData           = new GeneratedTask();
                            if (isset($CheckMstBeforeInsert)) {
                                $msg = "Record already exists.!";
                            } else {
                                if ($finalValue->type === "Approval") {
                                    $userId = 8;
                                } else if ($finalValue->type === "Auto triggered") {
                                    if ($finalValue->id < 3) {
                                        $ownerId = 11;
                                    } else {
                                        $ownerId = $lead->faculty_id;
                                    }
                                }
                                $insertData->mst_task_id        = $finalValue->id;
                                $insertData->lead_id            = $lead->id;
                                $insertData->token              = $token;
                                $insertData->task_generate_date = $today;
                                $insertData->task_due_date      = $today;
                                $insertData->task_owner         = $ownerId;
                                $insertData->task_subject       = $finalValue->task;
                                $insertData->task_status        = "Not Started";
                                $insertData->user_approval_id   = $userId;
                                $insertData->save();
                                if ($insertData) {
                                    $updateRecordInOrderstable = DB::table("orders")->where("id", $lead->id)->update([
                                        "latest_task_inserted" => $finalValue->id,
                                    ]);
                                    if ($ownerId > 0 || $userId > 0) {
                                        $getdataAfterInsert = GeneratedTask::where([["mst_task_id", "=", $finalValue->id], ["lead_id", "=", $lead->id]])->first();
                                        if ($finalValue->type === "Approval") {
                                            $message    = "Admin";
                                            $user_email = DB::table("clm_users")
                                                ->select("id", "email")
                                                ->where("id", $userId)->first();
                                            $link = url("/task-assign-for-approval", ["id" => $getdataAfterInsert->id, "user_id" => $user_email->id, "token" => $token]);
                                        } else if ($finalValue->type === "Auto triggered") {
                                            $message    = "Teacher";
                                            $user_email = $ownerId > 0 ? DB::table("clm_users")->select("id", "email")->where("id", $ownerId)->first() : '';

                                            $link = url("/assigned-task", ["id" => $getdataAfterInsert->id, "user_id" => $user_email->id, "token" => $token]);
                                        }
                                        $userRecord = DB::table("orders as o")
                                            ->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email", "s.name as state_name", "c.city as city_name")
                                            ->leftJoin("cities as c", "o.city", "=", "c.id")
                                            ->leftJoin("states as s", "o.state", "=", "s.id")
                                            ->where("o.id", $getdataAfterInsert->lead_id)
                                            ->first();
                                        $dataArray = [
                                            "subject"        => "CLM-" . $getdataAfterInsert->task_subject . "-" . $userRecord->school_name . "-" . $userRecord->city_name,
                                            "generated_date" => $getdataAfterInsert->task_generate_date,
                                            "due_date"       => $getdataAfterInsert->task_due_date,
                                            "task_subject"   => $getdataAfterInsert->task_subject,
                                            "school_name"    => $userRecord->school_name ? $userRecord->school_name : "N/A",
                                            "address"        => $userRecord->address ? $userRecord->address : "N/A",
                                            "city"           => $userRecord->city_name ? $userRecord->city_name : "N/A",
                                            "state"          => $userRecord->state_name ? $userRecord->state_name : "N/A",
                                            "Contact_number" => $userRecord->eu_mobile ? $userRecord->eu_mobile : "N/A",
                                            "email_id"       => $userRecord->eu_email ? $userRecord->eu_email : "N/A",
                                            "to_email"       => $user_email->email ? $user_email->email : "N/A",
                                            "cc_email"       => "imran.desai@ict360.com",
                                            "link"           => $link,
                                            // "formsg"=>$message
                                        ];
                                        //dd($dataArray);
                                        $msg = "Cron successfully created.!";
                                        //$this->EmailSend($dataArray);
                                    }
                                }
                            }
                            $userId  = null;
                            $ownerId = null;
                            $link    = "";
                            $message = "";
                        }
                    } else {
                        $msg = "There are no records as of " . $programDate . ".!";
                    }
                }
            } else {
                $msg = "Records not found";
            }
            echo $msg;
            exit;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return Response::json(["code" => Config('http-request.MASTER_KEY.Server-Error'), "status" => false, "message" => "Internal Server.!"])->withHeaders([
                "Content-Type" => "application/json",
                "Accept"       => "application.json",
            ]);
        }
    }

    public function renewalGenerateTask(Request $request)
    {
        try {
            $msg     = $daycount     = $task_gen     = "";
            $userId  = null;
            $ownerId = null;
            $link    = "";
            $message = "";
            $leads   = DB::table("orders")
                ->join("tbl_renewal_lead_task_process_record as t", "orders.id", "=", "t.lead_id")
                ->select(
                    "orders.id",
                    "t.id as task_process_id",
                    "orders.stage",
                    "orders.add_comm",
                    "t.program_initiation_date",
                    "t.latest_task_inserted",
                    "orders.faculty_id",
                    "orders.data_ref"
                )
                ->where("orders.agreement_type", "Renewal")
                ->whereNotNull("t.program_initiation_date")
                ->where(function ($query) {
                    $query->where("t.latest_task_inserted", "<", 21)
                        ->orWhereNull("t.latest_task_inserted");
                })->get();
            
            if ($leads->count() > 0) {
                foreach ($leads as $key => $lead) {
                  
                    $today = Carbon::today();
                    $programDate = Carbon::now();
                    
                    if ($lead->program_initiation_date <= $programDate) {
                        $daycount = $programDate->diffInDays($lead->program_initiation_date);
                    } else {
                        $daycount = 0;
                    }

                    $getTask = DB::table("mst_task")
                        ->select("id", "task", "day", "task_gen", "type")
                        ->where("day", $daycount)
                        ->whereNotNull("day")
                        ->whereNotIn("id", [7, 9])
                        ->get();

                        // dd($getTask);

                    if ($getTask->count() > 0) {
                        $token = Str::random(60);
                        foreach ($getTask as $key2 => $finalValue) {
                            $CheckMstBeforeInsert = GeneratedTask::where([["mst_task_id", "=", $finalValue->id], ["lead_id", "=", $lead->id], ["renewal_tasks_process_id", "=", $lead->task_process_id]])->first();
                            
                            $insertData = new GeneratedTask();
                            if (isset($CheckMstBeforeInsert)) {
                                $msg = "Record already exists.!";
                            } else {
                                if ($finalValue->type === "Approval") {
                                    $userId = 8;
                                } else if ($finalValue->type === "Auto triggered") {
                                    if ($finalValue->id < 3) {
                                        $ownerId = 11;
                                    } else {
                                        $ownerId = $lead->faculty_id;
                                    }
                                }
                                $insertData->mst_task_id        = $finalValue->id;
                                $insertData->lead_id            = $lead->id;
                                $insertData->token              = $token;
                                $insertData->task_generate_date = $today;
                                $insertData->task_due_date      = $today;
                                $insertData->task_owner         = $ownerId;
                                $insertData->task_subject       = $finalValue->task;
                                $insertData->task_status        = "Not Started";
                                $insertData->user_approval_id   = $userId;
                                $insertData->renewal_tasks_process_id   = $lead->task_process_id;
                                $insertData->save();
                                if ($insertData) {
                                    $updateRecordInOrderstable = DB::table("tbl_renewal_lead_task_process_record")->where("id", $lead->task_process_id)->update([
                                        "latest_task_inserted" => $finalValue->id,
                                    ]);
                                    if ($ownerId > 0 || $userId > 0) {
                                        $getdataAfterInsert = GeneratedTask::where([["mst_task_id", "=", $finalValue->id], ["lead_id", "=", $lead->id], ["renewal_tasks_process_id", "=", $lead->task_process_id]])->first();
                                        if ($finalValue->type === "Approval") {
                                            $message    = "Admin";
                                            $user_email = DB::table("clm_users")
                                                ->select("id", "email")
                                                ->where("id", $userId)->first();
                                            $link = url("/task-assign-for-approval", ["id" => $getdataAfterInsert->id, "user_id" => $user_email->id, "token" => $token]);
                                        } else if ($finalValue->type === "Auto triggered") {
                                            $message    = "Teacher";
                                            $user_email = $ownerId > 0 ? DB::table("clm_users")->select("id", "email")->where("id", $ownerId)->first() : '';

                                            $link = url("/assigned-task", ["id" => $getdataAfterInsert->id, "user_id" => $user_email->id, "token" => $token]);
                                        }
                                        $userRecord = DB::table("orders as o")
                                            ->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email", "s.name as state_name", "c.city as city_name")
                                            ->leftJoin("cities as c", "o.city", "=", "c.id")
                                            ->leftJoin("states as s", "o.state", "=", "s.id")
                                            ->where("o.id", $getdataAfterInsert->lead_id)
                                            ->first();
                                        $dataArray = [
                                            "subject"        => "CLM-" . $getdataAfterInsert->task_subject . "-" . $userRecord->school_name . "-" . $userRecord->city_name,
                                            "generated_date" => $getdataAfterInsert->task_generate_date,
                                            "due_date"       => $getdataAfterInsert->task_due_date,
                                            "task_subject"   => $getdataAfterInsert->task_subject,
                                            "school_name"    => $userRecord->school_name ? $userRecord->school_name : "N/A",
                                            "address"        => $userRecord->address ? $userRecord->address : "N/A",
                                            "city"           => $userRecord->city_name ? $userRecord->city_name : "N/A",
                                            "state"          => $userRecord->state_name ? $userRecord->state_name : "N/A",
                                            "Contact_number" => $userRecord->eu_mobile ? $userRecord->eu_mobile : "N/A",
                                            "email_id"       => $userRecord->eu_email ? $userRecord->eu_email : "N/A",
                                            "to_email"       => $user_email->email ? $user_email->email : "N/A",
                                            "cc_email"       => "imran.desai@ict360.com",
                                            "link"           => $link,
                                            // "formsg"=>$message
                                        ];
                                        // dd($dataArray);
                                        $msg = "Cron successfully created.!";
                                        //$this->EmailSend($dataArray);
                                    }
                                }
                            }
                            $userId  = null;
                            $ownerId = null;
                            $link    = "";
                            $message = "";
                        }
                    } else {
                        $msg = "There are no records as of " . $programDate . ".!";
                    }
                }
            } else {
                $msg = "Records not found";
            }
            echo $msg;
            exit;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return Response::json(["code" => Config('http-request.MASTER_KEY.Server-Error'), "status" => false, "message" => "Internal Server.!"])->withHeaders([
                "Content-Type" => "application/json",
                "Accept"       => "application.json",
            ]);
        }
    }

    public function EmailSend($dataArray)
    {
        $emailData = [
            "subject"        => $dataArray['subject'],
            "generated_date" => $dataArray['generated_date'],
            "due_date"       => $dataArray['due_date'],
            "task_subject"   => $dataArray['task_subject'],
            "school_name"    => $dataArray['school_name'],
            "address"        => $dataArray['address'],
            "city"           => $dataArray['city'],
            "state"          => $dataArray['state'],
            "Contact_number" => $dataArray['Contact_number'],
            "email_id"       => $dataArray['email_id'],
            "link"           => $dataArray['link'],
            //"forMSG"=>$dataArray['formsg']

        ];
        $result = Mail::to($dataArray['to_email'])->cc($dataArray['cc_email'])->send(new SendEmail($emailData));
        // $result= Mail::to('pradeep.chahal@arkinfo.in')->cc('virendra.kumar@arkinfo.in')->send(new SendEmail($emailData));
        if ($result === null) {
            echo "Email sent successfully";
        } else {
            echo "Email sent failed";
        }

    }

    public function renewalGenerateTaskForOldTask(Request $request)
    {
        try {
            $msg = "No new tasks created.";
            $today = Carbon::today();
            $programDate = Carbon::now();

            $leads = DB::table("orders")
                ->join("tbl_renewal_lead_task_process_record as t", "orders.id", "=", "t.lead_id")
                ->select(
                    "orders.id",
                    "t.id as task_process_id",
                    "orders.stage",
                    "orders.add_comm",
                    "t.program_initiation_date",
                    "t.latest_task_inserted",
                    "orders.faculty_id",
                    "orders.data_ref"
                )
                ->where("orders.agreement_type", "Renewal")
                ->whereNotNull("t.program_initiation_date")
                ->where(function ($query) {
                        $query->where("t.latest_task_inserted", "<", 21)
                            ->orWhereNull("t.latest_task_inserted");
                    })->get();

            if ($leads->count() > 0) {
                foreach ($leads as $lead) {
                    $startDate = Carbon::parse($lead->program_initiation_date);
                    $diffInDays = $startDate->diffInDays($today);

                    for ($i = 0; $i <= $diffInDays; $i++) {
                        $getTask = DB::table("mst_task")
                            ->select("id", "task", "day", "task_gen", "type")
                            ->where("day", $i)
                            ->whereNotIn("id", [7, 9])
                            ->whereNotNull("day")
                            ->get();

                        if ($getTask->count() > 0) {
                            $token = Str::random(60);
                            foreach ($getTask as $finalValue) {
                                $alreadyExists = GeneratedTask::where([
                                    ["mst_task_id", "=", $finalValue->id],
                                    ["lead_id", "=", $lead->id],
                                    ["renewal_tasks_process_id", "=", $lead->task_process_id]
                                ])->first();

                                if (!$alreadyExists) {
                                    $userId = null;
                                    $ownerId = null;
                                    $backDate = $startDate->copy()->addDays($i);

                                    if ($finalValue->type === "Approval") {
                                        $userId = 8;
                                    } else if ($finalValue->type === "Auto triggered") {
                                        $ownerId = ($finalValue->id < 3) ? 11 : $lead->faculty_id;
                                    }

                                    $insertData = new GeneratedTask();
                                    $insertData->mst_task_id = $finalValue->id;
                                    $insertData->lead_id = $lead->id;
                                    $insertData->token = $token;
                                    $insertData->task_generate_date = $backDate;
                                    $insertData->task_due_date = $backDate;
                                    $insertData->task_owner = $ownerId;
                                    $insertData->task_subject = $finalValue->task;
                                    $insertData->task_status = "Not Started";
                                    $insertData->user_approval_id = $userId;
                                    $insertData->renewal_tasks_process_id = $lead->task_process_id;
                                    $insertData->save();

                                    DB::table("tbl_renewal_lead_task_process_record")
                                        ->where("id", $lead->task_process_id)
                                        ->update(["latest_task_inserted" => $finalValue->id]);

                                    // Optional Email Notification Section
                                    if ($ownerId > 0 || $userId > 0) {
                                        $getTaskData = GeneratedTask::where([
                                            ["mst_task_id", "=", $finalValue->id],
                                            ["lead_id", "=", $lead->id],
                                            ["renewal_tasks_process_id", "=", $lead->task_process_id]
                                        ])->first();

                                        if ($finalValue->type === "Approval") {
                                            $user_email = DB::table("clm_users")
                                                ->select("id", "email")
                                                ->where("id", $userId)->first();
                                            $link = url("/task-assign-for-approval", ["id" => $getTaskData->id, "user_id" => $user_email->id, "token" => $token]);
                                        } else {
                                            $user_email = DB::table("clm_users")
                                                ->select("id", "email")
                                                ->where("id", $ownerId)->first();
                                            $link = url("/assigned-task", ["id" => $getTaskData->id, "user_id" => $user_email->id, "token" => $token]);
                                        }

                                        $userRecord = DB::table("orders as o")
                                            ->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email", "s.name as state_name", "c.city as city_name")
                                            ->leftJoin("cities as c", "o.city", "=", "c.id")
                                            ->leftJoin("states as s", "o.state", "=", "s.id")
                                            ->where("o.id", $getTaskData->lead_id)
                                            ->first();

                                        $dataArray = [
                                            "subject"        => "CLM-" . $getTaskData->task_subject . "-" . $userRecord->school_name . "-" . $userRecord->city_name,
                                            "generated_date" => $getTaskData->task_generate_date,
                                            "due_date"       => $getTaskData->task_due_date,
                                            "task_subject"   => $getTaskData->task_subject,
                                            "school_name"    => $userRecord->school_name ?? "N/A",
                                            "address"        => $userRecord->address ?? "N/A",
                                            "city"           => $userRecord->city_name ?? "N/A",
                                            "state"          => $userRecord->state_name ?? "N/A",
                                            "Contact_number" => $userRecord->eu_mobile ?? "N/A",
                                            "email_id"       => $userRecord->eu_email ?? "N/A",
                                            "to_email"       => $user_email->email ?? "N/A",
                                            "cc_email"       => "imran.desai@ict360.com",
                                            "link"           => $link,
                                        ];

                                        // Uncomment to send email
                                        // $this->EmailSend($dataArray);
                                    }

                                    $msg = "Cron successfully created backdated tasks.";
                                }
                            }
                        }
                    }
                }
            } else {
                $msg = "No leads found.";
            }

            echo $msg;
            exit;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return Response::json([
                "code" => Config('http-request.MASTER_KEY.Server-Error'),
                "status" => false,
                "message" => "Internal Server Error."
            ])->withHeaders([
                "Content-Type" => "application/json",
                "Accept"       => "application.json",
            ]);
        }
    }
}
