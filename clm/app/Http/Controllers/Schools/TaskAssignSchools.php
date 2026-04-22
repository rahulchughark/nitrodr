<?php

namespace App\Http\Controllers\Schools;

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
use Illuminate\Support\Facades\Crypt;
use Validator;
use Response;
use Exception;
use Config;
use App\User;
use App\Order;

class TaskAssignSchools extends Controller
{
    public function AllAssignTaskSchools(Request $request)
    {  
        try {
            $encryptedString ='';
            $encryptedAssign ='';
            $trainerName='';
            $FacultyList=[];
            $StatesList=[];
            if(!empty(request()->query('string')) && !empty( request()->query('assign')) && Crypt::decrypt( request()->query('assign'))==="trainer-assign"){
                $encryptedString =request()->query('string');
                $encryptedAssign =request()->query('assign');

            }
           $allFaculty= GetAllDataHelpers:: AllFACULTY();
           $allStates= GetAllDataHelpers:: AllStates();
           $CitesByState= GetAllDataHelpers:: GetAllCityByState($alldata="allcites");
           if($allFaculty->count()>0){
            $FacultyList=$allFaculty;
           }
           if($allStates->count()>0){
            $StatesList=$allStates;
           }
            $data = ["title" => "Schools Report","encryptedString"=>$encryptedString, "encryptedAssign"=>$encryptedAssign,"faculty"=>$FacultyList,"state"=>$StatesList,"stateF"=>request()->query('state') ?? '',"cityF"=>request()->query('city_name') ?? '',"agreement_typeF"=>request()->query('agreement_type') ?? '', "trainerF"=>request()->query('trainer') ?? '',"cites"=>$CitesByState];
            return view("schools.all-assign-task-schools", compact('data'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
    }
    public function GetSchoolTaskAsignData(Request $request)
    {
        try {
            $encryptedID = '';
            if (!empty($request->encryptedString) && !empty($request->encryptedAssign) && Crypt::decrypt($request->encryptedAssign) === "trainer-assign") {
                $encryptedID = Crypt::decrypt($request->encryptedString);
            }

            $html = "";
            $getAllrecord = null;
    
            if (AuthHelper::users()->user_type === "FACULTY" || AuthHelper::users()->user_type === 'SALES' || AuthHelper::users()->user_type === 'HELPDESK') {
                if (!empty($request->CheckID)) {
                    $userId = AuthHelper::users()->id;

                    $getAllrecord = GeneratedTask::select(
                            "generated_task.id",
                            "generated_task.mst_task_id",
                            "generated_task.task_generate_date",
                            "generated_task.task_due_date",
                            "generated_task.task_owner",
                            "generated_task.task_subject",
                            "generated_task.task_status",
                            "o.school_name",
                            "o.agreement_type",
                            "generated_task.lead_id"
                        )
                        ->rightJoin("orders as o", "o.id", "=", "generated_task.lead_id") // ensures schools appear even without tasks
                        ->leftJoin("clm_users as cu", "cu.id", "=", "generated_task.updated_by")
                        ->where(function ($query) use ($userId) {
                            $query->where("generated_task.task_owner", $userId)
                                ->orWhere("o.faculty_id", $userId); // include assigned schools
                        })
                        ->where(function ($query) use ($request) {
                            $query->where("generated_task.id", $request->CheckID)
                                ->orWhereNull("generated_task.id"); // include schools with no task
                        });
                } else {
                    if (!empty($request->encryptedString) && !empty($request->encryptedAssign) && Crypt::decrypt($request->encryptedAssign) === "trainer-assign") {
                        $getAllrecord = DB::table('orders as o')
                            ->select(
                                'generated_task.id',
                                'generated_task.mst_task_id',
                                'generated_task.task_owner',
                                'generated_task.lead_id',
                                'o.school_name',
                                'o.contact',
                                'o.school_email',
                                'o.city',
                                'o.state',
                                'o.pincode',
                                'o.agreement_type',
                                'cn.city as city_name'
                            )
                            ->leftJoin('generated_task', 'generated_task.lead_id', '=', 'o.id')
                            ->leftJoin('cities as cn', 'o.city', '=', 'cn.id')
                            ->where(function ($query) use ($encryptedID) {
                                $query->where('generated_task.task_owner', $encryptedID)
                                    ->orWhere('o.faculty_id', $encryptedID);
                            });

                    } else {
                    $userId = AuthHelper::users()->id;

                    $getAllrecord = GeneratedTask::select(
                            "generated_task.id",
                            "generated_task.mst_task_id",
                            "generated_task.task_generate_date",
                            "generated_task.task_due_date",
                            "generated_task.task_owner",
                            "generated_task.task_subject",
                            "generated_task.task_status",
                            "o.school_name",
                            "generated_task.lead_id",
                            "cu.name as updated_by",
                            "o.contact",
                            "o.school_email",
                            "o.agreement_type",
                            "o.city",
                            "o.state",
                            "o.pincode",
                            "cn.city as city_name"
                        )
                        ->rightJoin("orders as o", "o.id", "=", "generated_task.lead_id") // include schools even if no task
                        ->leftJoin("clm_users as cu", "cu.id", "=", "generated_task.updated_by")
                        ->leftJoin("cities as cn", "o.city", "=", "cn.id")
                        ->where(function ($query) use ($userId) {
                            $query->where("generated_task.task_owner", $userId)
                                ->orWhere("o.faculty_id", $userId); // include assigned schools
                        });
                    }
                }
            } else {
                if (!empty($request->encryptedString) && !empty($request->encryptedAssign) && Crypt::decrypt($request->encryptedAssign) === "trainer-assign") {
                        $getAllrecord = DB::table('orders as o')
                            ->select(
                                'generated_task.id',
                                'generated_task.mst_task_id',
                                'generated_task.task_owner',
                                'generated_task.lead_id',
                                'o.school_name',
                                'o.contact',
                                'o.school_email',
                                'o.city',
                                'o.state',
                                'o.pincode',
                                'o.agreement_type',
                                'cn.city as city_name'
                            )
                            ->leftJoin('generated_task', 'generated_task.lead_id', '=', 'o.id')
                            ->leftJoin('cities as cn', 'o.city', '=', 'cn.id')
                            ->where(function ($query) use ($encryptedID) {
                                $query->where('generated_task.task_owner', $encryptedID)
                                    ->orWhere('o.faculty_id', $encryptedID);
                            });
                } else {
                    $getAllrecord = GeneratedTask::select(
                        "generated_task.id",
                        "generated_task.mst_task_id",
                        "generated_task.task_owner",
                        "generated_task.lead_id",
                        "o.school_name",
                        "o.contact",
                        "o.school_email",
                        "o.city",
                         "o.state",
                         "o.agreement_type",
                        "o.pincode",
                        "cn.city as city_name"
                    )
                    ->leftJoin("orders as o", "o.id", "=", "generated_task.lead_id")
                    ->leftJoin("cities as cn", "o.city", "=", "cn.id")
                    ->whereNotNull("generated_task.task_owner");
                }
            }
    
            if (!empty($request->state)) {
                $getAllrecord->where( "o.state", $request->state);
            }
            if (!empty($request->city_name)) {
                $getAllrecord->where("o.city", $request->city_name);
            }
            if (!empty($request->trainer)) {
                $getAllrecord->where(function ($query) use ($request) {
                    $query->where('generated_task.task_owner', $request->trainer)
                        ->orWhere('o.faculty_id', $request->trainer);
                });
            }

            if (!empty($request->agreement_type)) {
                $getAllrecord->where("o.agreement_type", $request->agreement_type);
            }
    
            $Allresult = $getAllrecord->groupBy("generated_task.lead_id")->get();
            $totalRecords = $Allresult->count();
    
            if ($totalRecords > 0) {
                foreach ($Allresult as $key => $item) {
                    if($item['agreement_type']=='Renewal')
                    {
                        $urll = 'renewal_lead_view';
                    }else{
                        $urll = 'lead_view';
                    }
                    $getAssignTotalSchool = GeneratedTask::where('lead_id', $item->lead_id)
                        ->whereNotNull('task_owner')
                        ->distinct('task_owner')
                        ->count('task_owner');
                    
                    $html .= "
                        <tr>
                            <td>" . ($key + 1) . "</td>
                            <td>
                             <a href='".($item->school_name!='' ? URL("/".$urll."/".$item->lead_id." ") :"javavoidpoint(0)")."' style='cursor:".($item->school_name!='' ?'pointer' :'default')." ' target=".($item->school_name!='' ?'_blank' :'').">". ($item->school_name ?? 'N/A')."</a>
                            </td>
                            <td>" . ($item->contact ?? "N/A") . "</td>
                            <td>" . ($item->school_email ?? "N/A") . "</td>
                            <td>" . ($item->city_name ?? "N/A") . "</td>
                            <td>" . ($item->agreement_type ?? "N/A") . "</td>
                            <td>" . ($item->pincode ?? "N/A") . "</td>";
                    if (AuthHelper::users()->user_type === 'ADMIN' || AuthHelper::users()->user_type === 'SUPERADMIN') {
                        $html .= "<td>
                            <span style='cursor:" . ($getAssignTotalSchool > 0 ? 'pointer' : 'default') . "' 
                                  data-bs-toggle='modal' 
                                  data-bs-target='" . ($getAssignTotalSchool > 0 ? '#exampleModalCenterSchool' : '') . "' 
                                  onclick='" . ($getAssignTotalSchool > 0 ? "GetdataByTaskStatus(" . $item->lead_id . ", \"$item->school_name\")" : "return false;") . "'>
                                " . $getAssignTotalSchool . "
                            </span>
                        </td>";
                    }
                    $html .= "</tr>";
                }
    
                return response()->json([
                    'html' => $html,
                    'data' => $Allresult,
                    'recordsTotal' => $totalRecords,
                ]);
            } else {
               ?>
                        <script>
                         toastr.error("No records found!");
                        </script>
                        <?php
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
    

    public function GetAssignTrainerNameBySchool(Request $request){
        try {
             $rules=[
             "mstID"=>"required"
             ];
             $validators=Validator::make( $request->all(),$rules);
             if($validators->fails()){
                $failedRules=$validators->getMessageBag()->toArray();
                $errorMsg="";
                if(isset($failedRules['mstID']))
                $errorMsg=$failedRules['mstID'][0];
             return  Response::json(array("code"=>400,"status"=>false,"message"=>$errorMsg));
             }else{
                // if(AuthHelper::users()->user_type!=='ADMIN' && AuthHelper::users()->user_type!=='SUPERADMIN'){
                //     $getAssignTotalSchool = GeneratedTask::where('lead_id', $request->mstID)
                //     ->leftjoin("clm_users as trainer","trainer.id","=","generated_task.task_owner")
                //     ->where("generated_task.task_owner",AuthHelper::users()->id)
                //     ->whereNotNull('task_owner')
                //     ->select('generated_task.task_owner',"trainer.name","trainer.email","trainer.mobile") 
                //     ->distinct()
                //     ->get();
                // }
                // else{
                //     $getAssignTotalSchool = GeneratedTask::where('lead_id', $request->mstID)
                //     ->leftjoin("clm_users as trainer","trainer.id","=","generated_task.task_owner")
                //     ->whereNotNull('task_owner')
                //     ->select('generated_task.task_owner',"trainer.name","trainer.email","trainer.mobile") 
                //     ->distinct()
                //     ->get();
                // }
                $getAssignTotalSchool = GeneratedTask::where('lead_id', $request->mstID)
                    ->leftjoin("clm_users as trainer","trainer.id","=","generated_task.task_owner")
                    ->whereNotNull('task_owner')
                    ->select('generated_task.task_owner',"trainer.name","trainer.email","trainer.mobile") 
                    ->distinct()
                    ->get();
                if($getAssignTotalSchool->count()>0){
                  $html="";
                  foreach ($getAssignTotalSchool as $key => $item) {
                     $html.="
                     <tr>
                      <td>" . ++$key . "</td>
                     <td>".($item->name ?? "N/A")."</td>
                     <td>".($item->email ?? "N/A")."</td>
                     <td>".($item->mobile ?? "N/A")."</td>
                     </tr>
                     ";
                  }
                  return $html;
                }else{
                    ?>
                    <script>
                     toastr.error("This school does not have an assigned trainer.");
                    </script>
                    <?php
                }
             }
        } catch (\Exception $e) {
            ?>
                    <script>
                     toastr.error("Something went wrong.!");
                    </script>
                    <?php
        }
    }

    public function GetCitesByStates(Request $request)
    {  
        try {
            $rules=[
                "stateString"=>"required",
                ];
                $validators=Validator::make( $request->all(),$rules);
                if($validators->fails()){
                   $failedRules=$validators->getMessageBag()->toArray();
                   $errorMsg="";
                   if(isset($failedRules['stateString']))
                   $errorMsg=$failedRules['stateString'][0];
                  return  Response::json(array("code"=>400,"status"=>false,"message"=>$errorMsg));
                }else{
                    if(!empty($request->stateString)){
                        ?>
                        <option value="">---Select City---</option>
                        <?php
                        $CitesByState= GetAllDataHelpers:: GetAllCityByState($request->stateString);
                        $selectedCity = $request->selectedCity;
                         foreach ($CitesByState as $key => $city) {
                           ?>
                           <option value="<?=  $city->id ?>"><?=  $city->city ?></option>
                           <?php
                         }
                    }
                }
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
    }

}//end of class
