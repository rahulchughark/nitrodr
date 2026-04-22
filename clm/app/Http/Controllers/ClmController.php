<?php
    namespace App\Http\Controllers;

    use App\CLMActivity;
    use App\GeneratedTask;
    use App\Helpers\AuthHelper;
    use App\Helpers\GetAllDataHelpers;
    use App\Mail\SendEmail;
    use App\Order;
    use App\TaskAnswer;
    use App\TaskGenDataStatic;
    use App\TblLeadContact;
    use App\LeadModifyLog;
    use Auth;
    use DB;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Str;
    use Response;
    use Validator;
    use Illuminate\Support\Carbon;

    class ClmController extends Controller
    {
        public function demo($id)
        {
            $data['ques'] = DB::table('task_questions')->where('task_id', $id)->get();
            return view('demo', $data);
        }

        public function clmLeadView($id)
        {
            //echo $_GET['tab'];die;
            try {
                if (! empty($_GET['tab'])) {
                    $tab = $_GET['tab'];
                } else {
                    $tab = "other";
                }
                $getLeadData = Order::
                    select("orders.id", "orders.r_name as reseller_name", "orders.r_email as reseller_email", "orders.r_user as submitted_by", "orders.source as lead_source", "orders.sub_lead_source", "orders.school_board", "orders.is_group", "orders.group_name", "orders.address", "orders.pincode", "orders.eu_name", "orders.eu_email", "eu_landline", "orders.eu_mobile", "orders.eu_designation", "orders.school_name as organization_name", "orders.created_date", "p.product_name", "tpp.product_type", "tpp.id as type_id", "tp.product_id", "states.name as state_name", "cities.city as city_name", "orders.program_initiation_date")
                    ->leftjoin("tbl_lead_product as tp", "orders.id", "=", "tp.lead_id")
                    ->leftjoin("tbl_product as p", "tp.product_id", "=", "p.id")
                    ->leftjoin("tbl_product_pivot as tpp", "tp.product_type_id", "=", "tpp.id")
                    ->leftjoin("states", "states.id", "=", "orders.state")
                    ->leftjoin("cities", "cities.id", "=", "orders.city")
                    ->find($id);
                $programInitiationDate = $getLeadData->program_initiation_date;
                if (isset($getLeadData)) {
                    return view('lead_view', ['result' => $getLeadData, "lead_id" => $id, "tab" => $tab, 'programInitiationDate' => $programInitiationDate]);
                } else {
                    echo "Data is not available in this lead.Please check another lead.!";
                }
            } catch (\Exception $e) {
                return $e->getMessage();
                echo "Something went wrong.!";
            }
        }

        public function clmRenewalLeadView($id)
        {
            try {
                if (! empty($_GET['tab'])) {
                    $tab = $_GET['tab'];
                } else {
                    $tab = "other";
                }
                $getLeadData = Order::
                    select("orders.id", "orders.r_name as reseller_name", "orders.r_email as reseller_email", "orders.r_user as submitted_by", "orders.source as lead_source", "orders.sub_lead_source", "orders.school_board", "orders.is_group", "orders.group_name", "orders.address", "orders.pincode", "orders.eu_name", "orders.eu_email", "eu_landline", "orders.eu_mobile", "orders.eu_designation", "orders.school_name as organization_name", "orders.created_date", "p.product_name", "tpp.product_type", "tpp.id as type_id", "tp.product_id", "states.name as state_name", "cities.city as city_name", "orders.program_initiation_date")
                    ->leftjoin("tbl_lead_product as tp", "orders.id", "=", "tp.lead_id")
                    ->leftjoin("tbl_product as p", "tp.product_id", "=", "p.id")
                    ->leftjoin("tbl_product_pivot as tpp", "tp.product_type_id", "=", "tpp.id")
                    ->leftjoin("states", "states.id", "=", "orders.state")
                    ->leftjoin("cities", "cities.id", "=", "orders.city")
                    ->find($id);
                $programInitiationDate = $getLeadData->program_initiation_date;
                if (isset($getLeadData)) {
                    return view('renewal_lead_view', ['result' => $getLeadData, "lead_id" => $id, "tab" => $tab, 'programInitiationDate' => $programInitiationDate]);
                } else {
                    echo "Data is not available in this lead.Please check another lead.!";
                }
            } catch (\Exception $e) {
                return $e->getMessage();
                echo "Something went wrong.!";
            }
        }

        public function taskFeedback($id)
        {
            $data['ques'] = DB::table('task_questions')->where('task_id', $id)->get();
            $data['id']   = $id;
            return view('task_feedback', $data);
        }

        public function taskSave(Request $req)
        {
            // dd($req);
            $requestParams = $req->all();
            if ($req->task_gen_id == 0 && $req->task_id == 9) {
                $trainingCounts          = GeneratedTask::where('mst_task_id', $req->task_id)->where('lead_id', $req->lead_id)->count();
                $token                   = Str::random(60);
                $gen                     = new GeneratedTask();
                $gen->mst_task_id        = $req->task_id;
                $gen->lead_id            = $req->lead_id;
                $gen->token              = $token;
                $gen->task_generate_date = date('Y-m-d');
                $gen->task_due_date      = $req->task_due_date;
                $gen->task_owner         = $req->task_owner;
                $gen->task_subject       = 'Tool Training ' . ($trainingCounts + 1);
                $gen->task_status        = 'Not Started';
                $gen->save();

                $token              = Str::random(60);
                $getdataAfterInsert = GeneratedTask::find($gen->id);
                $message            = "Teacher";
                $user_email         = DB::table("clm_users")->select("id", "email")->where("id", $req->task_owner)->first();
                $link               = url("/assigned-task", ["id" => $getdataAfterInsert->id, "user_id" => $user_email->id, "token" => $token]);
                $userRecord         = DB::table("orders as o")
                    ->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email", "s.name as state_name", "c.city as city_name", "o.agreement_type")
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
                    // "to_email"=>"pradeep.chahal@arkinfo.in",
                    "to_email"       => $user_email ? $user_email->email : "N/A",
                    // "cc_email"=>"virendra.kumar@arkinfo.in",
                    "cc_email"       => "imran.desai@ict360.com",
                    "link"           => $link,
                    // "formsg"=>$message
                ];
                $mailS = $this->EmailSend($dataArray);

                $task_gen_id = $gen->id;
                $gendata     = GeneratedTask::find($req->task_gen_id);
            } else {
                $task_gen_id = $req->task_gen_id;
                $gendata     = GeneratedTask::find($task_gen_id);
                if ($req->status) {
                    $gendata->task_status     = $req->status;
                    $gendata->updated_by      = Auth::user()->id;
                    $gendata->reschedule_date = $req->status == 'Re-scheduled' ? $req->rescheduleDate : '';
                    $gendata->reschedule_time = $req->status == 'Re-scheduled' ? $req->rescheduleTime : '';
                    $gendata->save();
                }
            }
            if (isset($req->name44) && $req->name44 == 'Yes' && $req->task_gen_id != 0) {
                $checkparent = GeneratedTask::where('mst_task_id', $req->task_id)->where('lead_id', $req->lead_id)->where('host_gen_task_id', $task_gen_id)->count();
                if ($checkparent == 0) {
                    $trainingCounts                 = GeneratedTask::where('mst_task_id', $req->task_id)->where('lead_id', $req->lead_id)->count();
                    $token                          = Str::random(60);
                    $insertData                     = new GeneratedTask();
                    $insertData->mst_task_id        = $req->task_id;
                    $insertData->lead_id            = $req->lead_id;
                    $insertData->token              = $token;
                    $insertData->task_generate_date = $req->name93;
                    $insertData->task_due_date      = $req->name93;
                    $insertData->task_owner         = $req->name94;
                    $taskSub                        = DB::table('mst_task')->where('id', $gendata->mst_task_id)->value('task');
                    $insertData->task_subject       = $taskSub . " " . ($trainingCounts + 1);
                    $insertData->task_status        = "Not Started";
                    $insertData->host_gen_task_id   = $task_gen_id;
                    $insertData->save();

                    $getdataAfterInsert = GeneratedTask::find($insertData->id);
                    $message            = "Teacher";
                    $user_email         = DB::table("clm_users")->select("id", "email")->where("id", $req->name94)->first();
                    $link               = url("/assigned-task", ["id" => $getdataAfterInsert->id, "user_id" => $user_email->id, "token" => $token]);

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
                        // "to_email"=>"pradeep.chahal@arkinfo.in",
                        "to_email"       => $user_email ? $user_email->email : "N/A",
                        // "cc_email"=>"virendra.kumar@arkinfo.in",
                        "cc_email"       => "imran.desai@ict360.com",
                        "link"           => $link,
                        // "formsg"=>$message
                    ];
                    $mailS = $this->EmailSend($dataArray);
                    //   dd($mailS);
                }
            }
            $toDelete = DB::table('task_answers')->where('gen_task_id', $task_gen_id)->update(['is_deleted' => 1]);
            foreach ($requestParams as $key => $value) {
                $brokenKey = explode('e', $key);
                if ($brokenKey[0] == 'nam') {
                    if ($req->task_id == 2 && $brokenKey[1] == 8) {
                        $programIntitionDate = DB::table('orders')->where('id', $req->lead_id)->update(['program_initiation_date' => $value]);
                    } elseif ($req->task_id == 2 && $brokenKey[1] == 92) {
                        $oldTaskOwner        = DB::table('orders')->where('id', $req->lead_id)->value('faculty_id');
                        $programIntitionDate = DB::table('orders')->where('id', $req->lead_id)->update(['faculty_id' => $value]);
                        $checkRenewalTaskId  = DB::table('generated_task')->where('id', $req->task_gen_id)->value('renewal_tasks_process_id');
                        if ($programIntitionDate && $oldTaskOwner != $value) {
                            if($checkRenewalTaskId > 0){
                                $tasksOwnerChange = DB::table('generated_task')->where('lead_id', $req->lead_id)->where('renewal_tasks_process_id', $checkRenewalTaskId)->whereNull('user_approval_id')->where('mst_task_id', '>', '2')->update(['task_owner' => $value]);
                                $tasksListForMail = DB::table('generated_task')->where('lead_id', $req->lead_id)->where('renewal_tasks_process_id', $checkRenewalTaskId)->where('mst_task_id', '>', '2')->where('task_owner', $value)->get();
                            }else{
                                $tasksOwnerChange = DB::table('generated_task')->where('lead_id', $req->lead_id)->whereNull('user_approval_id')->where('mst_task_id', '>', '2')->update(['task_owner' => $value]);
                                $tasksListForMail = DB::table('generated_task')->where('lead_id', $req->lead_id)->where('mst_task_id', '>', '2')->where('task_owner', $value)->get();
                            }
                            if ($tasksListForMail) {
                                foreach ($tasksListForMail as $taskM) {
                                    $token         = Str::random(60);
                                    $tokenUpdate   = DB::table('generated_task')->where('id', $taskM->id)->update(['token' => $token]);
                                    $mailDataTask  = GeneratedTask::find($taskM->id);
                                    $approvalCheck = DB::table('mst_task')->where('id', $mailDataTask->mst_task_id)->value('type');
                                    if ($approvalCheck == "Approval") {
                                        $message    = "Admin";
                                        $user_email = DB::table("clm_users")->select("id", "email")->where("id", $value)->first();
                                        $link       = url("/task-assign-for-approval", ["id" => $mailDataTask->id, "user_id" => $user_email->id, "token" => $token]);
                                    } else if ($approvalCheck == "Auto triggered") {
                                        $message    = "Teacher";
                                        $user_email = DB::table("clm_users")->select("id", "email")->where("id", $value)->first();

                                        $link = url("/assigned-task", ["id" => $mailDataTask->id, "user_id" => $user_email->id, "token" => $token]);
                                    }
                                    $userRecord = DB::table("orders as o")
                                        ->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email", "s.name as state_name", "c.city as city_name")
                                        ->leftJoin("cities as c", "o.city", "=", "c.id")
                                        ->leftJoin("states as s", "o.state", "=", "s.id")
                                        ->where("o.id", $mailDataTask->lead_id)
                                        ->first();

                                    $dataArray = [
                                        "subject"        => "CLM-" . $mailDataTask->task_subject . "-" . $userRecord->school_name . "-" . $userRecord->city_name,
                                        "generated_date" => $mailDataTask->task_generate_date,
                                        "due_date"       => $mailDataTask->task_due_date,
                                        "task_subject"   => $mailDataTask->task_subject,
                                        "school_name"    => $userRecord->school_name ? $userRecord->school_name : "N/A",
                                        "address"        => $userRecord->address ? $userRecord->address : "N/A",
                                        "city"           => $userRecord->city_name ? $userRecord->city_name : "N/A",
                                        "state"          => $userRecord->state_name ? $userRecord->state_name : "N/A",
                                        "Contact_number" => $userRecord->eu_mobile ? $userRecord->eu_mobile : "N/A",
                                        "email_id"       => $userRecord->eu_email ? $userRecord->eu_email : "N/A",
                                        "to_email"       => $user_email ? $user_email : "N/A",
                                        "cc_email"       => "imran.desai@ict360.com",
                                        "link"           => $link,
                                        // "formsg"=>$message
                                    ];
                                    $this->EmailSend($dataArray);
                                    // dd($mailDataTask);
                                }
                            }
                        }

                    }
                    $ans = TaskAnswer::create([
                        'task_id'     => $req->task_id,
                        'gen_task_id' => $task_gen_id,
                        'lead_id'     => $req->lead_id,
                        'question_id' => $brokenKey[1],
                        'answer'      => gettype($value) == 'array' ? implode(',', $value) : $value,
                    ]);
                }
            }
            $agreement_typeL = DB::table('orders')->where('id', $req->lead_id)->value('agreement_type');
            //return redirect()->back()->with('success', 'Success.');
            if ($agreement_typeL == 'Renewal') {
                return redirect("/renewal_lead_view/$req->lead_id?&tab=" . $req->fortab . " ")->with('success', 'Success.');
            } else {
                return redirect("/lead_view/$req->lead_id?&tab=" . $req->fortab . " ")->with('success', 'Success.');
            }
        }

        public function clmModelData(Request $req)
        {
            // $clmQ =DB::table('mst_task')->where('id',$req->task_id)->pluck('task');
            $data['lead_id']       = $req->lead_id;
            $data['task_id']       = $req->task_id;
            $data['gen_task_id']   = $req->gen_task_id;
            $data['task_gen_data'] = $req->gen_task_id != 0 ? DB::table('generated_task')->leftJoin('clm_users', 'generated_task.task_owner', '=', 'clm_users.id')->where('generated_task.id', $req->gen_task_id)->select('generated_task.mst_task_id', 'generated_task.id', 'generated_task.lead_id', 'generated_task.task_generate_date', 'generated_task.task_due_date', 'generated_task.task_subject', 'generated_task.task_status', 'generated_task.reschedule_date', 'generated_task.reschedule_time', 'generated_task.host_gen_task_id', 'generated_task.user_approval_id', 'generated_task.current_status', 'clm_users.name as task_owner')->first() : '';
            $data['task_name']     = $req->gen_task_id != 0 ? $data['task_gen_data']->task_subject : 'Tool Training';

            $data['faculty'] = DB::table('clm_users')->select('id', 'name')->whereIn('user_type', ['FACULTY', 'HELPDESK'])->orWhere('id', 8)->get();
            if ($req->task_id == '9' && $req->gen_task_id != 0) {
                $data['grade_id'] = DB::table('grade_wise_tools')->select('grade')->distinct()->get();
                $data['answers']  = DB::table('task_answers')->where('gen_task_id', $req->gen_task_id)->where('is_deleted', 0)->pluck('answer', 'question_id');

                if (isset($data['answers']) && $data['answers']->isNotEmpty()) {
                    $data['grade']              = isset($data['answers'][39]) ? explode(",", $data['answers'][39]) : '';
                    $data['not_req']            = isset($data['answers'][40]) ? explode(",", $data['answers'][40]) : '';
                    $data['covered_tool']       = isset($data['answers'][41]) ? explode(",", $data['answers'][41]) : '';
                    $data['to_be_covered_tool'] = isset($data['answers'][45]) ? explode(",", $data['answers'][45]) : '';
                    if ($data['task_gen_data']->host_gen_task_id != 0) {
                        $task     = GeneratedTask::find($req->gen_task_id);
                        $toolsNot = $this->getParentTools($task);
                        // dd($toolsNot);
                        $data['tools']               = DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                        $toolsNot                    = $data['not_req'] ? array_merge($toolsNot, $data['not_req']) : $toolsNot;
                        $data['toolsForCovered']     = DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                        $toolsNot                    = $data['covered_tool'] ? array_merge($toolsNot, $data['covered_tool']) : $toolsNot;
                        $data['toolsForToBeCovered'] = DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();

                    } else {
                        $data['tools']               = $data['grade'] != '' ? DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->select('id', 'tool')->get() : collect();
                        $data['toolsForCovered']     = $data['grade'] != '' ? ($data['not_req'] != '' ? DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $data['not_req'])->select('id', 'tool')->get() : $data['tools']) : collect();
                        $data['toolsForToBeCovered'] = $data['grade'] != '' ? ($data['not_req'] != '' && $data['covered_tool'] != '' ? DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $data['not_req'])->whereNotIn('id', $data['covered_tool'])->select('id', 'tool')->get() : ($data['not_req'] != '' ? DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $data['not_req'])->select('id', 'tool')->get() : ($data['covered_tool'] != '' ? DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $data['covered_tool'])->select('id', 'tool')->get() : $data['tools']))) : collect();
                    }
                } else {
                    if ($data['task_gen_data']->host_gen_task_id != 0) {
                        $data['answers'] = DB::table('task_answers')->where('gen_task_id', $data['task_gen_data']->host_gen_task_id)->where('is_deleted', 0)->pluck('answer', 'question_id');
                        $data['answers']->put(44, '');
                        $data['answers']->put(93, '');
                        $data['answers']->put(94, '');
                        $data['answers']->put(95, '');
                        $data['answers']->put(42, '');
                        $data['answers']->put(43, '');
                        // dd($data['answers']);
                        $data['grade']               = isset($data['answers'][39]) ? explode(",", $data['answers'][39]) : '';
                        $data['not_req']             = '';
                        $data['covered_tool']        = isset($data['answers'][45]) ? explode(",", $data['answers'][45]) : '';
                        $task                        = GeneratedTask::find($req->gen_task_id);
                        $toolsNot                    = $this->getParentTools($task);
                        $data['tools']               = DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                        $data['toolsForCovered']     = DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                        $toolsNot                    = $data['covered_tool'] ? array_merge($toolsNot, $data['covered_tool']) : $toolsNot;
                        $data['toolsForToBeCovered'] = DB::table('grade_wise_tools')->whereIn('grade', $data['grade'])->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                    }
                }
            } else if ($req->task_id == '9' && $req->gen_task_id == 0) {
                $data['grade_id']      = DB::table('grade_wise_tools')->select('grade')->distinct()->get();
                $taskGenData           = new TaskGenDataStatic();
                $data['task_gen_data'] = $taskGenData;
            } else {
                $questions = DB::table('task_questions')
                    ->where('task_id', $req->task_id)
                    ->get(['id', 'question', 'input_name', 'ques_type', 'select_option', 'event_function', 'input_id', 'dont_show']);
                // dd($req->task_id);
                $answers = DB::table('task_answers')
                    ->where('gen_task_id', $req->gen_task_id)
                    ->where('is_deleted', 0)
                    ->pluck('answer', 'question_id');

                $data['ques'] = $questions->map(function ($question) use ($answers) {
                    $answer = $answers[$question->id] ?? null;
                    return [
                        'question'       => $question->question,
                        'input_name'     => $question->input_name,
                        'ques_type'      => $question->ques_type,
                        'answer'         => $answer,
                        'select_option'  => $question->select_option,
                        'event_function' => $question->event_function,
                        'input_id'       => $question->input_id,
                        'dont_show'      => $question->dont_show,
                    ];
                })->all();

                if ($req->task_id == 2) {
                    $data['facultySelected'] = DB::table('task_answers')->where('gen_task_id', $req->gen_task_id)->where('question_id', 92)->where('is_deleted', 0)->value('answer');
                    // $data['facultySelected'] = DB::table('orders')->where('id', $req->lead_id)->value('faculty_id');
                    $data['program_initiation_date'] = DB::table('orders')->where('id', $req->lead_id)->value('program_initiation_date');
                    // dd($data['facultySelected']);
                }
            }
            if ($req->gen_task_id != 0) {
                if ($data['task_gen_data']->task_status == 'Re-scheduled') {
                    if ($req->task_id != 2 && $req->task_id != 1) {
                        $data['display'] = "";
                    } else {
                        $data['display'] = "display:none";
                    }
                } else {
                    $data['display'] = "display:none";
                }
            } else {
                $data['display'] = "display:none";
            }
            $data['currentDate']  = date('Y-m-d');
            $data['tabPageValue'] = $req->tabPageValue;
            return view('clm_model_data', $data)->render();
        }

        public function gradeTools(Request $req)
        {
            // dd($req);
            $task = GeneratedTask::find($req->gen_task_id);
            if ($task && $task->host_gen_task_id != 0) {
                $toolsNot = $this->getParentTools($task);
                if ($req->fieldId == '40') {
                    $toolsNot = array_merge($toolsNot, $req->notReqIds);
                    $tools    = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                } else if ($req->fieldId == '41') {
                    // dd($req->fieldId);
                    $toolsNot = $req->notReqIds ? array_merge($toolsNot, $req->notReqIds) : $toolsNot;
                    $toolsNot = array_merge($toolsNot, $req->coveredIds);
                    $tools    = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                } else {
                    $tools = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $toolsNot)->select('id', 'tool')->get();
                }
            } else {
                if ($req->fieldId == '40') {
                    $tools = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $req->notReqIds)->select('id', 'tool')->get();
                } else if ($req->fieldId == '41') {
                    if ($req->notReqIds && $req->coveredIds) {
                        $tools = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $req->notReqIds)->whereNotIn('id', $req->coveredIds)->select('id', 'tool')->get();
                    } else if ($req->notReqIds) {
                        $tools = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $req->notReqIds)->select('id', 'tool')->get();
                    } else {
                        $tools = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->whereNotIn('id', $req->coveredIds)->select('id', 'tool')->get();
                    }
                } else {
                    $tools = DB::table('grade_wise_tools')->whereIn('grade', $req->grade_id)->select('id', 'tool')->get();
                }
            }
            if ($tools->count() > 0) {
                return response()->json($tools);
            }
        }

        private function getParentTools(GeneratedTask $task)
        {
            $tools = [];
            while ($task && $task->host_gen_task_id != 0) {
                $notReqIds    = DB::table('task_answers')->where('gen_task_id', $task->host_gen_task_id)->where('question_id', 40)->where('is_deleted', 0)->value('answer');
                $toolsCovered = DB::table('task_answers')->where('gen_task_id', $task->host_gen_task_id)->where('question_id', 41)->where('is_deleted', 0)->value('answer');
                if ($notReqIds != '') {
                    $notReqIdsArr = explode(",", $notReqIds);
                    $tools        = array_merge($tools, $notReqIdsArr);
                }
                if ($toolsCovered != '') {
                    $toolsCoveredArr = explode(",", $toolsCovered);
                    $tools           = array_merge($tools, $toolsCoveredArr);
                }
                $task = GeneratedTask::find($task->host_gen_task_id);
            }
            return $tools;
        }

        public function TrackerTaskWise()
        {
            if (request("subjectId")) {
                $getSubject = request("subjectId");
            } else {
                $getSubject = [];
            }
            $dataSubject = [
                "idSubject" => implode(",", $getSubject),
            ];
            $resultSubject = GetAllDataHelpers::AllSubject();
            return view('reports/tracker_task_wise', compact('resultSubject', 'dataSubject', 'getSubject'));
        }
        public function GetTrackerTaskWiseData(Request $request)
        {
            try {
                $html         = "";
                $getAllrecord = DB::table("mst_task as m")->select("m.id", "m.task");
                if (! empty($request->subject)) {
                    $getAllrecord->whereIn("m.id", $request->subject);
                }
                $AllmstTask = $getAllrecord->get();
                if ($AllmstTask->count() > 0) {
                    foreach ($AllmstTask as $key => $item) {
                        if (AuthHelper::users()->user_type === "FACULTY" || AuthHelper::users()->user_type === 'SALES' || AuthHelper::users()->user_type === 'HELPDESK') {
                            list($getTotalNotStarted, $getTotalInProgress, $getTotalCompleted, $getTotalReScheduled, $getTotalCancelled) = array_map(function ($status) use ($item) {
                                return DB::table("generated_task")->where("mst_task_id", $item->id)->where("task_owner", AuthHelper::users()->id)->where("task_status", $status)->count();
                            }, ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"]);
                            $getTotalSchool = DB::table("generated_task")->where([["mst_task_id", "=", $item->id], ["task_owner", "=", AuthHelper::users()->id]])->count();
                        } else {
                            list($getTotalNotStarted, $getTotalInProgress, $getTotalCompleted, $getTotalReScheduled, $getTotalCancelled) = array_map(function ($status) use ($item) {
                                return DB::table("generated_task")->where("mst_task_id", $item->id)->where("task_status", $status)->count();
                            }, ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"]);
                            $getTotalSchool = DB::table("generated_task")->where([["mst_task_id", "=", $item->id]])->count();
                        }
                        $html .= "
<tr>
    <td>" . ++$key . "</td>
    <td>" . htmlspecialchars($item->task) . "</td>
    <td style='text-align:center'>
        <span style='cursor:" . ($getTotalSchool > 0 ? 'pointer' : 'default') . "'
              data-bs-toggle='modal'
              data-bs-target='" . ($getTotalSchool > 0 ? '#exampleModalCenterSchool' : '') . "'
              onclick='" . ($getTotalSchool > 0 ? "GetdataByTaskStatus(" . $item->id . ", \"Schools\")" : "return false;") . "'>
            " . $getTotalSchool . "
        </span>
    </td>
    <td style='text-align:center'>
        <span style='cursor:" . ($getTotalNotStarted > 0 ? 'pointer' : 'default') . "'
              data-bs-toggle='modal'
              data-bs-target='" . ($getTotalNotStarted > 0 ? '#exampleModalCenterSchool' : '') . "'
              onclick='" . ($getTotalNotStarted > 0 ? "GetdataByTaskStatus(" . $item->id . ", \"Not Started\")" : "return false;") . "'>
            " . $getTotalNotStarted . "
        </span>
    </td>
    <td style='text-align:center'>
        <span style='cursor:" . ($getTotalInProgress > 0 ? 'pointer' : 'default') . "'
              data-bs-toggle='modal'
              data-bs-target='" . ($getTotalInProgress > 0 ? '#exampleModalCenterSchool' : '') . "'
              onclick='" . ($getTotalInProgress > 0 ? "GetdataByTaskStatus(" . $item->id . ", \"In Progress\")" : "return false;") . "'>
            " . $getTotalInProgress . "
        </span>
    </td>
    <td style='text-align:center'>
        <span style='cursor:" . ($getTotalCompleted > 0 ? 'pointer' : 'default') . "'
              data-bs-toggle='modal'
              data-bs-target='" . ($getTotalCompleted > 0 ? '#exampleModalCenterSchool' : '') . "'
              onclick='" . ($getTotalCompleted > 0 ? "GetdataByTaskStatus(" . $item->id . ", \"Completed\")" : "return false;") . "'>
            " . $getTotalCompleted . "
        </span>
    </td>
    <td style='text-align:center'>
        <span style='cursor:" . ($getTotalReScheduled > 0 ? 'pointer' : 'default') . "'
              data-bs-toggle='modal'
              data-bs-target='" . ($getTotalReScheduled > 0 ? '#exampleModalCenterSchool' : '') . "'
              onclick='" . ($getTotalReScheduled > 0 ? "GetdataByTaskStatus(" . $item->id . ", \"Re-scheduled\")" : "return false;") . "'>
            " . $getTotalReScheduled . "
        </span>
    </td>
    <td style='text-align:center'>
        <span style='cursor:" . ($getTotalCancelled > 0 ? 'pointer' : 'default') . "'
              data-bs-toggle='modal'
              data-bs-target='" . ($getTotalCancelled > 0 ? '#exampleModalCenterSchool' : '') . "'
              onclick='" . ($getTotalCancelled > 0 ? "GetdataByTaskStatus(" . $item->id . ", \"Cancelled\")" : "return false;") . "'>
            " . $getTotalCancelled . "
        </span>
    </td>
</tr>
";

                    }
                } else {
                ?>
                   <script>
                    toastr.error("Task record not found!");
                   </script>
                   <?php
                       }
                                   return $html;
                               } catch (\Exception $e) {
                                   return "Something went wrong.!";
                               }
                           }
                           public function TrackerTrainerWise($id)
                           {

                               $data['id'] = $id;
                               return view('tracker_trainer_wise', $data);
                           }
                           public function TrackerCumulative($id)
                           {
                               $data['id'] = $id;
                               return view('tracker_cumulative', $data);
                           }
                           public function Dashboard($id)
                           {
                               $data['id'] = $id;
                               return view('dashboard', $data);
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
                               // $result= Mail::to('pradeep.chahal@arkinfo.in')->cc('virendra.kumar@arkinfo.in')->send(new SendEmail($emailData));
                               $result = Mail::to($dataArray['to_email'])->cc($dataArray['cc_email'])->send(new SendEmail($emailData));
                               if ($result != null) {
                               ?>
            <script>
                alert("Something went wrong");
            </script>
            <?php
                }
                    }

                    public function editClmForm(Request $req)
                    {
                        $data['lead_data']      = Order::select("eu_name", "eu_email", "eu_landline", "eu_mobile", "eu_designation")->find($req->lead_id);
                        $data['other_contacts'] = DB::table('tbl_lead_contact')->where('lead_id', $req->lead_id)->where('status', 1)->get();
                        $data['lead_id']        = $req->lead_id;
                        // dd($data['other_contacts']);
                        return view('edit_clm_model', $data)->render();
                    }

                    public function editLeadDetails(Request $req)
                    {
                        $data['lead_data']          = Order::find($req->lead_id);
                        $data['grade_signed_upArr'] = $data['lead_data'] ? explode(',', $data['lead_data']->grade_signed_up) : [];
                        $data['other_contacts']     = DB::table('tbl_lead_contact')->where('lead_id', $req->lead_id)->where('status', 1)->get();
                        $data['spocs']              = DB::table('clm_users')->whereIn('user_type', ['FACULTY', 'HELPDESK'])->orWhere('id', 8)->get();
                        $data['lead_id']            = $req->lead_id;
                        // dd($data['other_contacts']);
                        return view('edit_lead_details_model', $data)->render();
                    }

                    public function updateClmModelData(Request $req)
                    {

                        $prevId = DB::table('orders')->where('id', $req->lead_id)->value('faculty_id');
                        $prevN = $prevId ? (DB::table('clm_users')->where('id', $prevId)->value('name')) : '';
                        $modN = $req->spoc ? (DB::table('clm_users')->where('id', $req->spoc)->value('name')) : '';
                        $now = Carbon::now('Asia/Kolkata');
                        LeadModifyLog::Create(
                            [
                                'lead_id'        => $req->lead_id,
                                'type'        => 'CLM - Edit Faculty/SPOC ',
                                'previous_name'       => $prevN,
                                'modify_name'      => $modN,
                                'created_date'      => $now,
                                'created_by_clm' => AuthHelper::users()->id,
                            ]
                        );
                        $data                      = Order::find($req->lead_id);
                        $data->spoc                = $req->spoc;
                        $data->faculty_id          = $req->spoc;
                        $data->eu_name             = $req->eu_name;
                        $data->eu_email            = $req->eu_email;
                        $data->eu_mobile           = $req->eu_mobile;
                        $data->eu_designation      = $req->eu_designation;
                        $data->eu_person_name1     = $req->eu_person_name1;
                        $data->eu_mobile1          = $req->eu_mobile1;
                        $data->eu_designation1     = $req->eu_designation1;
                        $data->eu_email1           = $req->eu_email1;
                        $data->eu_person_name2     = $req->eu_person_name2;
                        $data->eu_mobile2          = $req->eu_mobile2;
                        $data->eu_email2           = $req->eu_email2;
                        $data->adm_name            = $req->adm_name;
                        $data->adm_designation     = $req->adm_designation;
                        $data->adm_email           = $req->adm_email;
                        $data->adm_mobile          = $req->adm_mobile;
                        $data->adm_alt_mobile      = $req->adm_alt_mobile;
                        $data->school_board        = $req->school_board;
                        $data->program_start_date  = $req->program_start_date;
                        $data->academic_start_date = $req->academic_start_date;
                        $data->academic_end_date   = $req->academic_end_date;
                        //$data->grade_signed_up = implode(", ", $req->grade_signed_up);
                        $data->grade_signed_up      = ! empty($req->grade_signed_up) ? implode(", ", $req->grade_signed_up) : null;
                        $data->quantity             = $req->quantity;
                        $data->purchase_no          = $req->purchase_no;
                        $data->application_date     = $req->application_date;
                        $data->purchase_deails      = $req->purchase_deails;
                        $data->license_period       = $req->license_period;
                        $data->is_app_erp           = $req->is_app_erp;
                        $data->ip_address           = $req->ip_address;
                        $data->labs_count           = $req->labs_count;
                        $data->system_count         = $req->system_count;
                        $data->os                   = $req->os;
                        $data->student_system_ratio = $req->student_system_ratio;
                        $data->lab_teacher_ratio    = $req->lab_teacher_ratio;
                        $data->save();
                        TblLeadContact::where('lead_id', $req->lead_id)->update([
                            'status' => 0,
                        ]);
                        if (!empty($req->spoc)) {
                            $a = GeneratedTask::where('lead_id', $req->lead_id)
                            ->whereNotIn('mst_task_id', [1,2])
                            ->update(['task_owner' => $req->spoc]);
                        }
//                         dd($a);
// die;
                        if (isset($req->contacts)) {
                            foreach ($req->contacts as $contact) {
                                TblLeadContact::Create(
                                    [
                                        'lead_id'        => $req->lead_id,
                                        'eu_name'        => $contact['eu_name'],
                                        'eu_email'       => $contact['eu_email'],
                                        'eu_mobile'      => $contact['eu_mobile'],
                                        'eu_designation' => $contact['eu_designation'],
                                    ]
                                );
                            }
                        }
                        return redirect()->back()->with('success', 'Success.');
                    }

                    ///// cerate new method for get count data in popup for report/tracker_task_wise
                    public function GetTrackerTaskWisePopupData(Request $request)
                    {
                        try {
                            $html = "";
                            if (! empty($request->mstID) && ! empty($request->status)) {
                                $getSchoolData = Order::leftJoin("generated_task as gt", "orders.id", "=", "gt.lead_id")
                                    ->leftJoin("city as c", "orders.city", "=", "c.id")
                                    ->where("gt.mst_task_id", $request->mstID)
                                    ->select(
                                        DB::raw("COALESCE(orders.school_name, 'N/A') as school_name"),
                                        DB::raw("COALESCE(orders.contact, 'N/A') as contact"),
                                        DB::raw("COALESCE(orders.school_email, 'N/A') as school_email"),
                                        DB::raw("COALESCE(c.name, 'N/A') as city"),
                                        DB::raw("COALESCE(orders.pincode, 'N/A') as pincode"),
                                        "gt.lead_id"
                                    );
                                $validStatuses = ["Not Started", "In Progress", "Completed", "Re-scheduled", "Cancelled"];
                                if (in_array($request->status, $validStatuses)) {
                                    $getSchoolData->where("gt.task_status", $request->status);
                                }
                                if (AuthHelper::users()->user_type === 'FACULTY' || AuthHelper::users()->user_type === 'SALES' || AuthHelper::users()->user_type === 'HELPDESK') {
                                    $getSchoolData->where([["gt.task_owner", AuthHelper::users()->id]]);
                                }
                                $results = $getSchoolData->get();
                                if ($results->count() > 0) {
                                    foreach ($results as $key => $item) {
                                        $html .= "
            <tr>
                                    <td>" . ++$key . "</td>
                                    <td><a href='" . URL("lead_view/" . $item->lead_id) . "' target='_blank'>" . $item->school_name . "</a></td>
                                    <td>" . $item->contact . "</td>
                                    <td>" . $item->school_email . "</td>
                                    <td>" . $item->city . "</td>
                                    <td>" . $item->pincode . "</td>
                                </tr>
            ";
                                    }
                                }
                                return $html;
                            } else {
                                return "Sent request should not be empty.!";
                            }
                        } catch (\Exception $e) {
                            return $e->getMessage();
                            return "Something went wrong.!";
                        }
                    }

                    public function ActivityTracker(Request $req)
                    {
                        try {
                            if($req->lead_id==0){
                                if (AuthHelper::users()->user_type === "FACULTY" || AuthHelper::users()->user_type === 'SALES' || AuthHelper::users()->user_type === 'HELPDESK') {
                                    $data['getSchools'] = GeneratedTask::select("o.school_name", "o.id", "o.agreement_type")
    ->leftJoin("orders as o", "o.id", "=", "generated_task.lead_id")
    ->where("generated_task.task_owner", AuthHelper::users()->id)
    ->whereNotNull("generated_task.task_owner")
    ->whereNotNull("o.school_name")
    ->where(function ($q) {
        $q->where("o.agreement_type", "Renewal")
          ->orWhere(function ($sub) {
              $sub->where("o.agreement_type", "Fresh")
                  ->whereNotExists(function ($sq) {
                      $sq->select(DB::raw(1))
                         ->from("orders as r")
                         ->whereRaw("r.parent_id = o.id")
                         ->where("r.agreement_type", "Renewal");
                  });
          });
    })
    ->orderBy("o.school_name")
    ->groupBy("generated_task.lead_id", "o.school_name", "o.id", "o.agreement_type") // to avoid SQL strict group by issues
    ->get();
                                }else{
$data['getSchools'] = GeneratedTask::select("o.school_name", "o.id", "o.agreement_type")
    ->leftJoin("orders as o", "o.id", "=", "generated_task.lead_id")
    ->whereNotNull("generated_task.task_owner")
    ->whereNotNull("o.school_name")
    ->where(function ($q) {
        $q->where("o.agreement_type", "Renewal")
          ->orWhere(function ($sub) {
              $sub->where("o.agreement_type", "Fresh")
                  ->whereNotExists(function ($sq) {
                      $sq->select(DB::raw(1))
                         ->from("orders as r")
                         ->whereRaw("r.parent_id = o.id")
                         ->where("r.agreement_type", "Renewal");
                  });
          });
    })
    ->orderBy("o.school_name")
    ->groupBy("generated_task.lead_id", "o.school_name", "o.id", "o.agreement_type") // avoid ONLY_FULL_GROUP_BY issue
    ->get();
                                }
                            }
                            if ($req->usages === 'editActivity') {
                                $titleName            = "Edit Activity";
                                $page                 = 'reports/edit-activity';
                                $data['activityID']   = $req->activityID;
                                $data['lead_id']      = $req->lead_id;
                                $data['tabPageValue'] = $req->tabPageValue;
                            } else {
                                $titleName            = "Add Activity";
                                $page                 = 'reports/activity-tracker';
                                $data['lead_id']      = $req->lead_id;
                                $data['tabPageValue'] = $req->tabPageValue;
                            }
                            $data['title'] = $titleName;
                            return view($page, $data)->render();
                        } catch (\Exception $e) {

                            return "Something went wrong.!";
                        }
                    }

                    public function ActivitySave(Request $req)
                    {
                        try {
                            if (isset($req->pagecheck) && $req->pagecheck === "editactivity" && ! empty($req->id)) {
                                $rules = [
                                    "follow_up_solution" => "required|string",
                                ];
                            } else {
                                $rules = [
                                    "subject"                => "required|string",
                                    "poc_name"               => "required|string",
                                    "poc_designation"        => "required|string",
                                    "solution_provided"      => "required|string",
                                    "follow_up_status"       => "required|string",
                                    "remark"                 => "nullable|string",
                                    "follow_up_date"         => "nullable|date",
                                    "solution_provided_date" => "nullable|date",
                                    "follow_up_reason"       => "nullable|string",
                                ];
                                if ($req->follow_up_status == 1) {
                                    $rules["follow_up_date"]   = "required|date";
                                    $rules["follow_up_reason"] = "required|string";
                                }
                            }
                            $validators = Validator::make($req->all(), $rules);
                            if ($validators->fails()) {
                                $failedRules = $validators->getMessageBag()->toArray();
                                $errorMsg    = "";

                                if (isset($req->pagecheck) && $req->pagecheck === "editactivity" && ! empty($req->id)) {
                                    if (isset($failedRules['follow_up_solution'])) {
                                        $errorMsg = $failedRules['follow_up_solution'][0];
                                    }

                                } else {
                                    if (isset($failedRules['subject'])) {
                                        $errorMsg = $failedRules['subject'][0];
                                    }

                                    if (isset($failedRules['poc_name'])) {
                                        $errorMsg = $failedRules['poc_name'][0];
                                    }

                                    if (isset($failedRules['poc_designation'])) {
                                        $errorMsg = $failedRules['poc_designation'][0];
                                    }

                                    if (isset($failedRules['solution_provided'])) {
                                        $errorMsg = $failedRules['solution_provided'][0];
                                    }

                                    if (isset($failedRules['follow_up_status'])) {
                                        $errorMsg = $failedRules['follow_up_status'][0];
                                    }

                                    if (isset($failedRules['remark'])) {
                                        $errorMsg = $failedRules['remark'][0];
                                    }

                                    if ($req->follow_up_status == 1) {
                                        if (isset($failedRules['follow_up_date'])) {
                                            $errorMsg = $failedRules['follow_up_date'][0];
                                        }

                                        if (isset($failedRules['follow_up_reason'])) {
                                            $errorMsg = $failedRules['follow_up_reason'][0];
                                        }

                                        if (isset($failedRules['solution_provided_date'])) {
                                            $errorMsg = $failedRules['solution_provided_date'][0];
                                        }

                                    }
                                }
                                return Response::json(["status" => false, "message" => $errorMsg], 400)->withHeaders([
                                    "Content-Type" => "application/json",
                                    "Accept"       => "application.json",
                                ]);
                            } else {
                                $leadID = $req->lead_id;
                                if ($req->follow_up_status == 1) {
                                    $status = 1;
                                } else {
                                    $status = 0;
                                }
                                if (isset($req->pagecheck) && $req->pagecheck === "editactivity" && ! empty($req->id)) {
                                    $Activity_task                     = CLMActivity::find($req->id);
                                    $Activity_task->follow_up_solution = $req->follow_up_solution;
                                    $Activity_task->status             = 0;
                                    $msg                               = "Activity has been successfully updated";
                                } else {
                                    $Activity_task                        = new CLMActivity();
                                    $Activity_task->created_by            = Auth::user()->id;
                                    $Activity_task->lead_id               = $leadID;
                                    $Activity_task->subject               = $req->subject;
                                    $Activity_task->poc_name              = $req->poc_name;
                                    $Activity_task->poc_designation       = $req->poc_designation;
                                    $Activity_task->solution_provided     = $req->solution_provided;
                                    $Activity_task->follow_up_status      = $req->follow_up_status;
                                    $Activity_task->follow_up_date        = $req->follow_up_date;
                                    $Activity_task->solution_rovided_date = $req->solution_provided_date;
                                    $Activity_task->follow_up_reason      = $req->follow_up_reason;
                                    $Activity_task->remark                = $req->remark;
                                    $Activity_task->status                = $status;
                                    $msg                                  = "Activity has been successfully generated";
                                }
                                $Activity_task->save();
                                return redirect("/lead_view/$leadID?&tab=" . $req->fortab . " ")->with('success', $msg);
                            }
                        } catch (\Exception $e) {
                            return "Something went wrong.!";
                        }
                    }

                    ///// cerate new method for get view data in popup for report/activity-tracker
                    public function GetActivityTrackerPopupData(Request $request)
                    {
                        try {
                            $getclm_activity = CLMActivity::select(
                                "clm_activity.id as clm_activity_id",
                                "clm_activity.subject",
                                "clm_activity.poc_name",
                                "clm_activity.poc_designation",
                                "clm_activity.solution_provided",
                                "clm_activity.follow_up_status",
                                "clm_activity.follow_up_solution",
                                "clm_activity.solution_rovided_date",
                                "o.school_name",
                                DB::raw("DATE_FORMAT(clm_activity.follow_up_date, '%d/%m/%Y') as formatted_follow_up_date"),
                                "clm_activity.follow_up_reason",
                                "clm_activity.remark",
                                "clm_activity.status",
                                DB::raw("DATE_FORMAT(clm_activity.created_at, '%d/%m/%Y') as formatted_created_at"),
                                "clm_users.name",
                                DB::raw("CASE WHEN clm_activity.status = 1 THEN 'Open' ELSE 'Closed' END as status_label"),
                                DB::raw("CASE WHEN clm_activity.follow_up_status = 1 THEN 'YES' ELSE 'NO' END as status_follow_up")
                            )
                                ->leftJoin("clm_users", "clm_users.id", "=", "clm_activity.created_by")
                                ->leftJoin("orders as o", "o.id", "=", "clm_activity.lead_id")
                                ->where("clm_activity.id", $request->activityID)
                                ->whereNotNull("clm_activity.created_by")
                                ->first();

                            if ($getclm_activity) {
                                $poc_name_designation = (! empty($getclm_activity->poc_name) && ! empty($getclm_activity->poc_designation))
                                ? $getclm_activity->poc_name . " - " . $getclm_activity->poc_designation
                                : "N/A";

                                $html = "
                    <tr>
                        <td width='35%'>Activity Date</td>
                        <td width='65%'>" . ($getclm_activity->formatted_created_at ?? "N/A") . "</td>
                    </tr>
                    tr>
                        <td>School Name</td>
                        <td>" . ($getclm_activity->school_name ?? "N/A") . "</td>
                    </tr>
                    <tr>
                        <td>Subject</td>
                        <td>" . ($getclm_activity->subject ?? "N/A") . "</td>
                    </tr>
                    <tr>
                        <td>Faculty Name</td>
                        <td>" . ($getclm_activity->name ?? "N/A") . "</td>
                    </tr>
                    <tr>
                        <td>POC Name - Designation</td>
                        <td>" . $poc_name_designation . "</td>
                    </tr>
                    <tr>
                        <td>Solution Provided</td>
                        <td>" . ($getclm_activity->solution_provided ?? "N/A") . "</td>
                    </tr>
                     <tr>
                        <td>Solution Provided Date</td>
                        <td>" . ($getclm_activity->solution_rovided_date ?? "N/A") . "</td>
                    </tr>
                    <tr>
                        <td>Follow-up Status</td>
                        <td>" . ($getclm_activity->status_follow_up ?? "N/A") . "</td>
                    </tr>
                    <tr>
                        <td>Follow-up Date</td>
                        <td>" . ($getclm_activity->formatted_follow_up_date ?? "N/A") . "</td>
                    </tr>
                    <tr>
                        <td>Follow-up Reason</td>
                        <td>" . ($getclm_activity->follow_up_reason ?? "N/A") . "</td>
                    </tr>";

                                if ($getclm_activity->follow_up_solution != null && ! empty($getclm_activity->follow_up_solution)) {
                                    $html .= "
                        <tr>
                            <td>Follow-up Solution</td>
                            <td>" . $getclm_activity->follow_up_solution ?? "N/A" . "</td>
                        </tr>";
                                }

                                $html .= "
    <tr>
        <td>Remark</td>
        <td>" . ($getclm_activity->remark ?? "N/A") . "</td>
    </tr>
    <tr>
        <td>Status</td>
        <td>" . ($getclm_activity->status_label ?? "N/A") . "</td>
    </tr>
                ";

                                return $html;
                            } else {
                                return "No data found for the given activity ID.";
                            }
                        } catch (\Exception $e) {
                            return "Error: " . $e->getMessage();
                        }

                    }

            } //end of class
