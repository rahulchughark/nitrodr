<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\GeneratedTask;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function usersList(Request $req){
        
        return view('users_list');
    } 
    
    public function GetUsersListData(Request $req){
        
        // try {
            $html="";
                $getAllrecord=DB::table("clm_users")->get();
                if($getAllrecord->count()>0){
                    $i = 1;
                    $url = asset('public/images/share.svg');
                    foreach ($getAllrecord as $data) {
                        $html.="
                        <tr>
                                                <td>".$i."</td>
                                                <td>".$data->name." </td>
                                                <td>".$data->email."</td>
                                                <td>".$data->mobile."</td>
                                                <td>".$data->user_type."</td>
                                                <td style='cursor:pointer' onclick='edit_user_model(".$data->id.",1)'><button type=button class='btn btn-primary btn-small px-2'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'><path d='M12.8995 6.85453L17.1421 11.0972L7.24264 20.9967H3V16.754L12.8995 6.85453ZM14.3137 5.44032L16.435 3.319C16.8256 2.92848 17.4587 2.92848 17.8492 3.319L20.6777 6.14743C21.0682 6.53795 21.0682 7.17112 20.6777 7.56164L18.5563 9.68296L14.3137 5.44032Z'></path></svg></button></td>
                                                <td style='text-align: center; cursor:pointer' onclick='edit_user_model(".$data->id.",2)'><span class='btn btn-primary btn-xs px-2'><img src=".$url." height='16px' alt='Transfer Data'></div></td>
                                            </tr>
                        ";
                        $i++;
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
        // } catch (\Exception $e) {
        //    return "Something went wrong.!";
        // }
    }
    
    public function editUserDataModel(Request $req){
        if($req->edit_type == 'edit_user'){
            $data['user'] =DB::table('clm_users')->where('id',$req->user_id)->first();
            return view('edit_user_model',$data)->render();
        }else if($req->edit_type == 'transfer_data'){
            $data['user'] =DB::table('clm_users')->where('id',$req->user_id)->first();
            $data['faculty_list'] =DB::table('clm_users')->whereIn('user_type',['FACULTY','HELPDESK'])->orWhere('id',8)->get();
            return view('transfer_user_model',$data)->render();
        }
    }

    public function updateUser(Request $req)
    {
        $validatedData = $req->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|string|in:ADMIN,FACULTY,HELPDESK,SALES',
            'user_id' => 'required|integer',
            'mobile' => 'required',
        ]);
        $user = User::find($validatedData['user_id']);
        
        $user->name = $validatedData['name'];
        $user->password = isset($req['password']) && $req['password'] != '' ? Hash::make($req['password']) : $user->password;
        $user->user_type = $validatedData['user_type'];
        $user->mobile = $validatedData['mobile'];
        
        if($user->save()){
            return redirect()->back()->with('success', 'User updated successfully.');
        }else{
            return redirect()->back()->with('error', 'failed to update.');
        }
    }
    
    public function transferUserData(Request $req){
        $validatedData = $req->validate([
            'user' => 'required|integer|exists:users,id',
        ]);
        
        GeneratedTask::where('task_owner', $req->user_id)->update([
            'task_owner' => $req->user
        ]);
        return redirect()->back()->with('success', 'User updated successfully.');
        

    }
}
