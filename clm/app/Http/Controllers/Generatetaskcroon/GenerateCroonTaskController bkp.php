<?php

namespace App\Http\Controllers\Generatetaskcroon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Response;
use Exception;
use Config;
use App\GeneratedTask;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateCroonTaskController extends Controller
{

//    public function GenerateTask(Request $request){
//         try {
//             $msg="";
//             $leads = DB::table("orders")
//             ->select("id","stage","add_comm","program_initiation_date","latest_task_inserted")
//             ->where(function ($query) {
//                 $query->where('stage', 'PO/CIF Issued')
//                     ->where('add_comm', 'Advance Payment Received');
//             })->orWhereNotNull('program_initiation_date')
//             ->where('latest_task_inserted', '<', 21)
//             ->get();
//     if($leads->count() > 0)
//       {
//     foreach ($leads as $key => $lead) {
//        if( $lead->program_initiation_date!=NULL && $lead->latest_task_inserted<20){
//         $programDate = Carbon::now();
//         $daycount=$programDate->diffInDays($lead->program_initiation_date);
//         $task_gen="Program initiation date";
//        }
//      else {
//         $getStageModify=DB::table("lead_modify_log")->select("id","lead_id","type","modify_name","created_date")
//         ->where("lead_id",$lead->id)
//         ->where("type","Stage")
//         ->where("modify_name","PO/CIF Issued")
//         ->whereNotNull("created_date")
//         ->limit(1)->orderBy("id","DESC")->first();
//  if(isset($getStageModify)){
//     $programDate = Carbon::now();
//     $StageDate=date("Y-m-d",strtotime($getStageModify->created_date));
//     $daycount=$programDate->diffInDays($StageDate);
//     $task_gen="EUPO Issued date";
//  }
//      }
//      $getTask=DB::table("mst_task")
//      ->select("id","task","day","task_gen")
//      ->where([["day","=",$daycount],["task_gen","=",$task_gen]])
//      ->whereNotNull("day")
//      ->get();

//      if(isset($getTask)){
//         foreach($getTask as $key2 =>$finalValue){
//             $CheckMstBeforeInsert= GeneratedTask::where([["mst_task_id", "=", $finalValue->id],["lead_id", "=", $lead->id]])->first();
//             $insertData=new GeneratedTask();
//             if(isset($CheckMstBeforeInsert)){
//                 $msg="Record already exists.!";
//             }
//             else{
//                 $insertData->mst_task_id=$finalValue->id;
//                 $insertData->lead_id=$lead->id;
//                 $insertData->task_generate_date=date("Y-m-d");
//                 $insertData->task_due_date=date("Y-m-d"); 
//                 $insertData->task_owner="Mohit";
//                 $insertData->task_subject=$finalValue->task;
//                 $insertData->task_status="Not Started";
//                 $insertData->save();
//                 if($insertData){
//                     $updateRecordInOrderstable=DB::table("orders")->where("id",$lead->id)->update([
//                         "latest_task_inserted"=>$finalValue->id
//                       ]);
//                     $msg="Cron successfully created.!";
//                 }
//             }
//         }
//      }
     
//     }
//       }else{
//         $msg = "Records not found";
//       }
//       echo $msg;
//       exit;
//         }
//         catch (\Exception $e) {
//             echo $e->getMessage();
//             return Response::json(array("code"=>Config('http-request.MASTER_KEY.Server-Error'),"status"=>false, "message"=>"Internal Server.!"))->withHeaders([
//                 "Content-Type"=>"application/json",
//                 "Accept"=>"application.json",
//             ]);
//         }
// }

// public function GenerateTask(Request $request){
//   try {
//      $msg=$daycount=$task_gen="";
//      $userId=NULL;
//      $ownerId=Null;
//       $leads = DB::table("orders")
//       ->select("id","stage","add_comm","program_initiation_date","latest_task_inserted","faculty_id")
//       ->where(function ($query) {
//           $query->where('stage', 'PO/CIF Issued')
//               ->where('add_comm', 'Advance Payment Received');
//       })->orWhereNotNull('program_initiation_date')
//       ->where('latest_task_inserted', '<', 21)
//       ->get();
// if($leads->count() > 0)
// {
// foreach ($leads as $key => $lead) {
//  if($lead->program_initiation_date!=NULL && $lead->latest_task_inserted<20){
//   $programDate = Carbon::now();
//   $daycount=$programDate->diffInDays($lead->program_initiation_date);
//  $task_gen="Program initiation date";
//  }
// else {
//   $getStageModify=DB::table("lead_modify_log")->select("id","lead_id","type","modify_name","created_date")
//   ->where("lead_id",$lead->id)
//   ->where("type","Stage")
//   ->where("modify_name","PO/CIF Issued")
//   ->whereNotNull("created_date")
//   ->limit(1)->orderBy("id","DESC")->get();
// if($getStageModify->count()>0){
// $programDate = Carbon::now();
// $StageDate=date("Y-m-d",strtotime($getStageModify[0]->created_date));
// $daycount=$programDate->diffInDays($StageDate);
// $task_gen="EUPO Issued date";
// }
// else{
//   echo $msg="EUPO Issue data is not in lead modify log.!";
//   exit;
// }
// }
// $getTask=DB::table("mst_task")
// ->select("id","task","day","task_gen","type")
// ->where([["day","=",$daycount],["task_gen","=",$task_gen]])
// ->whereNotNull("day")
// ->get();
// if($getTask->count()>0){
//   foreach($getTask as $key2 =>$finalValue){
//       $CheckMstBeforeInsert= GeneratedTask::where([["mst_task_id", "=", $finalValue->id],["lead_id", "=", $lead->id]])->first();
//       $insertData=new GeneratedTask();
//       if(isset($CheckMstBeforeInsert)){
//           $msg="Record already exists.!";
//       }
//       else{
//         if($finalValue->type==="Approval"){
//           $userId=8;
//         }
//         else if($finalValue->type==="Auto triggered"){
//           if($finalValue->id<3){
//             $ownerId=11;
//           }
//           else{
//             $ownerId=$lead->faculty_id;
//           }
//         }
//           $insertData->mst_task_id=$finalValue->id;
//           $insertData->lead_id=$lead->id;
//           $insertData->task_generate_date=date("Y-m-d");
//           $insertData->task_due_date=date("Y-m-d"); 
//           $insertData->task_owner=$ownerId;
//           $insertData->task_subject=$finalValue->task;
//           $insertData->task_status="Not Started";
//           $insertData->user_approval_id=$userId;
//           $insertData->save();
//           if($insertData){
//               $updateRecordInOrderstable=DB::table("orders")->where("id",$lead->id)->update([
//                   "latest_task_inserted"=>$finalValue->id
//                 ]);
//               $msg="Cron successfully created.!";
//               $this->EmailSend($dataArray);
//           }
          
//       }
//   }
// }
// else{
//   $msg="There are no records as of today.!";
// }

// }
// }else{
//   $msg = "Records not found";
// }
// echo $msg;
// exit;
//   }
//   catch (\Exception $e) {
//       echo $e->getMessage();
//       return Response::json(array("code"=>Config('http-request.MASTER_KEY.Server-Error'),"status"=>false, "message"=>"Internal Server.!"))->withHeaders([
//           "Content-Type"=>"application/json",
//           "Accept"=>"application.json",
//       ]);
//   }
// }


public function GenerateTask(Request $request){
  try {
     $msg=$daycount=$task_gen="";
     $userId=NULL;
     $ownerId=NULL;
    $link="";
    $message="";
      $leads = DB::table("orders")
      ->select("id","stage","add_comm","program_initiation_date","latest_task_inserted","faculty_id","data_ref")
      ->where(function ($query) {
          $query->where('stage', 'PO/CIF Issued')
              ->where('add_comm', 'Advance Payment Received');
      })->orWhereNotNull('program_initiation_date')
      ->where('latest_task_inserted', '<', 21)
      ->get();
      
    //   print_r($leads); die;
if($leads->count() > 0)
{
foreach ($leads as $key => $lead) {

 if($lead->program_initiation_date!=NULL && $lead->latest_task_inserted<19 && $lead->latest_task_inserted >1){
  $programDate = Carbon::now();
  if($lead->program_initiation_date<=$programDate){
    $daycount=$programDate->diffInDays($lead->program_initiation_date);
  }else{
    $daycount=0;
  }
 
 $task_gen="Program initiation date";
 }
else {
  $getStageModify=DB::table("lead_modify_log")->select("id","lead_id","type","modify_name","created_date")
  ->where("lead_id",$lead->id)
  ->where("type","Stage")
  ->where("modify_name","PO/CIF Issued")
  ->whereNotNull("created_date")
  ->limit(1)->orderBy("id","DESC")->get();
if($getStageModify->count()>0){
$programDate = Carbon::now();
$StageDate=date("Y-m-d",strtotime($getStageModify[0]->created_date));
if($getStageModify[0]->created_date<=$programDate){
  $daycount=$programDate->diffInDays($StageDate);
}
else{
  $daycount=0;
}
$task_gen="EUPO Issued date";
}
else{
  echo $msg="EUPO Issue data is not in lead modify log.!";
  exit;
}
}
$getTask=DB::table("mst_task")
->select("id","task","day","task_gen","type")
->where([["day","=",$daycount],["task_gen","=",$task_gen]])
->whereNotNull("day")
->get();
if($getTask->count()>0){
  $token= Str::random(60);
  foreach($getTask as $key2 =>$finalValue){
      $CheckMstBeforeInsert= GeneratedTask::where([["mst_task_id", "=", $finalValue->id],["lead_id", "=", $lead->id]])->first();
      $insertData=new GeneratedTask();
      if(isset($CheckMstBeforeInsert)){
          $msg="Record already exists.!";
      }
      else{
        if($finalValue->type==="Approval"){
          $userId=8;
        }
        else if($finalValue->type==="Auto triggered"){
          if($finalValue->id<3){
            $ownerId=11;
          }
          else{
            $ownerId=$lead->faculty_id;
          }
        }
          $insertData->mst_task_id=$finalValue->id;
          $insertData->lead_id=$lead->id;
          $insertData->token=$token;
          $insertData->task_generate_date=date("Y-m-d");
          $insertData->task_due_date=date("Y-m-d"); 
          $insertData->task_owner=$ownerId;
          $insertData->task_subject=$finalValue->task;
          $insertData->task_status="Not Started";
          $insertData->user_approval_id=$userId;
          $insertData->save();
          if($insertData){
              $updateRecordInOrderstable=DB::table("orders")->where("id",$lead->id)->update([
                  "latest_task_inserted"=>$finalValue->id
                ]);
$getdataAfterInsert=GeneratedTask::where([["mst_task_id", "=", $finalValue->id],["lead_id", "=", $lead->id]])->first();
 if($finalValue->type==="Approval"){
   $message="Admin";
   $user_email=DB::table("clm_users")
   ->select("id","email")
   ->where("id",$userId)->first();
   $link=url("/task-assign-for-approval",["id"=>$getdataAfterInsert->id,"user_id"=>$user_email->id, "token"=>$token]);
 }
 else if($finalValue->type==="Auto triggered"){
  $message="Teacher";
  $user_email=DB::table("clm_users")
  ->select("id","email")
  ->where("id",$ownerId)->first();
  $link=url("/assigned-task",["id"=>$getdataAfterInsert->id,"user_id"=>$user_email->id, "token"=>$token]);
 }
 $userRecord = DB::table("orders as o")
->select("o.school_name", "o.address", "o.city", "o.state", "o.eu_mobile", "o.eu_email","s.name as state_name","c.city as city_name")
->leftJoin("cities as c", "o.city", "=", "c.id")
->leftJoin("states as s", "o.state", "=", "s.id")
->where("o.id", $getdataAfterInsert->lead_id)
->first();
 $dataArray=[
  "subject"=>"CLM-".$getdataAfterInsert->task_subject."-".$userRecord->school_name."-".$userRecord->city_name,
  "generated_date"=>$getdataAfterInsert->task_generate_date,
  "due_date"=>$getdataAfterInsert->task_due_date,
  "task_subject"=>$getdataAfterInsert->task_subject,
  "school_name"=>$userRecord->school_name?$userRecord->school_name:"N/A",
  "address"=>$userRecord->address?$userRecord->address:"N/A",
  "city"=>$userRecord->city_name?$userRecord->city_name:"N/A",
  "state"=>$userRecord->state_name?$userRecord->state_name:"N/A",
  "Contact_number"=>$userRecord->eu_mobile?$userRecord->eu_mobile:"N/A",
  "email_id"=>$userRecord->eu_email?$userRecord->eu_email:"N/A",
  "to_email"=> $user_email? $user_email:"N/A",
  "cc_email"=> "imran.desai@ict360.com",
  "link"=>$link,
 // "formsg"=>$message
];
              $msg="Cron successfully created.!";
            //   $this->EmailSend($dataArray);
          }
      }
  }
}
else{
  $msg="There are no records as of today.!";
}

}
}else{
  $msg = "Records not found";
}
echo $msg;
exit;
  }
  catch (\Exception $e) {
      echo $e->getMessage();
      return Response::json(array("code"=>Config('http-request.MASTER_KEY.Server-Error'),"status"=>false, "message"=>"Internal Server.!"))->withHeaders([
          "Content-Type"=>"application/json",
          "Accept"=>"application.json",
      ]);
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
                    "link"=>$dataArray['link'],
                    //"forMSG"=>$dataArray['formsg']

    ];
    $result= Mail::to($dataArray['to_email'])
    ->cc($dataArray['cc_email'])
    ->send(new SendEmail($emailData));
    if($result===null){
        echo "Email sent successfully";
    }else{
        echo "Email sent failed";
    }
   
}

}
