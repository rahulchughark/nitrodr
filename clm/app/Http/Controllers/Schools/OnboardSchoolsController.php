<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\GetAllDataHelpers;
use App\Helpers\AuthHelper;
use App\GeneratedTask;
use App\Order;

class OnboardSchoolsController extends Controller
{
    public function index()
    {
        try {
            // $encryptedString ='';
            // $encryptedAssign ='';
            // $trainerName='';
            // $FacultyList=[];
            // $StatesList=[];
            // if(!empty(request()->query('string')) && !empty( request()->query('assign')) && Crypt::decrypt( request()->query('assign'))==="trainer-assign"){
            //     $encryptedString =request()->query('string');
            //     $encryptedAssign =request()->query('assign');

            // }
           $allFaculty= GetAllDataHelpers:: AllFACULTY();
           $allStates= GetAllDataHelpers:: AllStates();
           $CitesByState= GetAllDataHelpers:: GetAllCityByState($alldata="allcites");
           if($allFaculty->count()>0){
            $FacultyList=$allFaculty;
           }
           if($allStates->count()>0){
            $StatesList=$allStates;
           }
            $data = ["title" => "Onboard Schools","faculty"=>$FacultyList,"state"=>$StatesList,"stateF"=>request()->query('state') ?? '',"cityF"=>request()->query('city_name') ?? '', "trainerF"=>request()->query('trainer') ?? '',"cites"=>$CitesByState];
            return view("onboard-schools", compact('data'));
        } catch (\Exception $e) {
            return $e->getMessage();
            return "Something went wrong.!";
        }
    }

    public function getOnboardSchools(Request $request)
    {
        try {            
            $html = "";
            $getAllrecord = null;
      
                $getAllrecord = Order::select(
                    "orders.id",
                    "orders.school_name",
                    "orders.contact",
                    "orders.school_email",
                    "orders.city",
                    "orders.state",
                    "orders.pincode",
                    "orders.agreement_type",
                    "cn.city as city_name"
                )
                ->leftJoin("cities as cn", "orders.city", "=", "cn.id")
                ->where("orders.onboard_mail_sent",1)
                ->where(function($query) {
                    $query->whereNull('orders.program_initiation_date')
                        ->orWhere('orders.program_initiation_date', '');
                });
    
            // if (!empty($request->state)) {
            //     $getAllrecord->where( "o.state", $request->state);
            // }
            // if (!empty($request->city_name)) {
            //     $getAllrecord->where("o.city", $request->city_name);
            // }
            // if (!empty($request->trainer)) {
            //     $getAllrecord->where("generated_task.task_owner", $request->trainer);
            // }
    
            $Allresult = $getAllrecord->groupBy("orders.id")->get();
            $totalRecords = $Allresult->count();
    
            if ($totalRecords > 0) {
                foreach ($Allresult as $key => $item) {                    
                    $html .= "
                        <tr>
                            <td>" . ($key + 1) . "</td>
                            <td>
                             <a href='".($item->school_name!='' ? ($item->agreement_type == 'Renewal' ? URL("/renewal_lead_view/".$item->id." ") : URL("/lead_view/".$item->id." ")) :"javavoidpoint(0)")."' style='cursor:".($item->school_name!='' ?'pointer' :'default')." ' target=".($item->school_name!='' ?'_blank' :'').">". ($item->school_name ?? 'N/A')."</a>
                            </td>
                            <td>" . ($item->contact ?? "N/A") . "</td>
                            <td>" . ($item->school_email ?? "N/A") . "</td>
                            <td>" . ($item->city_name ?? "N/A") . "</td>
                            <td>" . ($item->pincode ?? "N/A") . "</td>
                            <td>" . ($item->agreement_type ?? "N/A") . "</td>";
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
}
