<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
namespace App\Helpers;
use Validator;
use Response;
use Exception;
use Config;
use App\User;

class GetAllDataHelpers
{
    public static function Allusers()
    {
        try {
            $getAllClmUser=User::select("id","name","user_type")->whereIn('user_type',['FACULTY','HELPDESK'])->orWhere('id',8)->get();
            if($getAllClmUser->count()>0){
                return $getAllClmUser;
            }
        } catch (\Exception $e) {
            return "Something went wrong.!";
        }
    }
    public static function AllSubject()
    {
        try {
            $getAllSubject=app('db')->table("mst_task")->select("id","task")->get();;
            if($getAllSubject->count()>0){
                return $getAllSubject;
            }
        } catch (\Exception $e) {
            return "Something went wrong.!";
        }
    }

    public static function AllStates()
    {
        try {
            $getAllStates=app('db')->table("states")->select("id","name")->get();;
            if($getAllStates->count()>0){
                return $getAllStates;
            }
        } catch (\Exception $e) {
            return "Something went wrong.!";
        }
    }

    public static function GetAllCityByState($stateID)
    {
        try {
            if($stateID==="allcites"){
                $getAllCites=app('db')->table("cities")->select("id","city","state_id")->get();
            }else{
                $getAllCites=app('db')->table("cities")->select("id","city","state_id")->where("state_id",$stateID)->get();
            }
            if($getAllCites->count()>0){
                return $getAllCites;
            }else{
                return "City not found in this state.";
            }
        } catch (\Exception $e) {
            return "Something went wrong.!";
        }
    }

    public static function AllFACULTY()
    {
        try {
            $getAllClmUser=User::select("id","name")->whereIn('user_type',['FACULTY'])->get();
            if($getAllClmUser->count()>0){
                return $getAllClmUser;
            }
        } catch (\Exception $e) {
            return "Something went wrong.!";
        }
    }
}
?>