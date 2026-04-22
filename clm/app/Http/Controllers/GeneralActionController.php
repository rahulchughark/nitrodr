<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\TblRenewalLeadTaskProcessRecord;

class GeneralActionController extends Controller
{
    public function saveProgramInitiation(Request $request){
        $request->validate([
            'lead_id' => 'required|exists:orders,id',
            'program_initiation_date' => 'required|date',
        ]);
    
        $lead = Order::findOrFail($request->lead_id);
        $lead->program_initiation_date = $request->program_initiation_date;
        $lead->program_start_date = $request->program_initiation_date;
        $lead->onboard_mail_sent = 2;
        $lead->save();

        $pid = TblRenewalLeadTaskProcessRecord::where('lead_id',$request->lead_id)->first();
        if($pid){
            $pid->program_initiation_date = $request->program_initiation_date;
            $pid->save();
        }
    
        return redirect()->back()->with('success', 'Program Initiation Date saved successfully.');
    }
}
