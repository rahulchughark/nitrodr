<?php

namespace App\Http\Controllers;

use App\Exports\GlobalExport;
use App\Helpers\ExportHelper;
use App\CLMActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\AuthHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function exportData(Request $request,$lead_id=null)
    {
        $getclm_activity = CLMActivity::select(
            "clm_activity.id as clm_activity_id",
            "clm_activity.subject",
            "clm_activity.poc_name",
            "clm_activity.poc_designation",
            "clm_activity.follow_up_date",
            "clm_activity.solution_rovided_date",
            "clm_activity.follow_up_reason",
            "clm_activity.created_at",
            "clm_users.name",
            "o.school_name",
            DB::raw("CASE WHEN clm_activity.status = 1 THEN 'Open' ELSE 'Closed' END as status_label")
        )
        ->leftJoin("clm_users", "clm_users.id", "=", "clm_activity.created_by")
        ->leftJoin("orders as o", "o.id", "=", "clm_activity.lead_id")
        ->whereNotNull("clm_activity.created_by");

        if (AuthHelper::users()->user_type !== 'ADMIN') {
            $getclm_activity->where([["clm_activity.created_by", "=", AuthHelper::users()->id],
                ["lead_id", "=", $lead_id]
            ]);
        } else if(AuthHelper::users()->user_type==='ADMIN'){//&& request('checktrue')=='condition'
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

        $getAllResult = $getclm_activity->orderBy("clm_activity.id", "DESC")->get();

        // Check if data exists
        if ($getAllResult->isEmpty()) {
            return back()->with('error', 'No data available for export.');
        }

        // Transform data to an array
        $data = $getAllResult->map(function ($item) {
            return [
                'created_at' => date("d/m/Y",strtotime($item->created_at)) ?? "N/A",
                'school_name' => $item->school_name ?? "N/A",
                'subject' => $item->subject ?? "N/A",
                'name' => $item->name ?? "N/A",
                'poc_name' => $item->poc_name ?? "N/A",
                'poc_designation' => $item->poc_designation ?? "N/A",
                'follow_up_date' => $item->follow_up_date ?? "N/A",
                'solution_rovided_date' => $item->solution_rovided_date ?? "N/A",
                'follow_up_reason' => $item->follow_up_reason ?? "N/A",
                'follow_up_solution' => $item->follow_up_reason ?? "N/A",
                'remark' => $item->follow_up_reason ?? "N/A",
                'status_label' => $item->status_label ?? "N/A",
            ];
        })->toArray();

        // Define column headings
        $headings = ['Activity Date', 'School Name', 'Subject', 'Faculty Name', 'POC Name', 'POC Designation', 'Follow-Up Date', 'Solution Provided Date', 'Follow-Up Reason','Follow-Up Solution','Remark', 'Status'];

        // Export data
        return ExportHelper::export('clm_activity_data.csv', new GlobalExport($data, $headings));
    }
}
