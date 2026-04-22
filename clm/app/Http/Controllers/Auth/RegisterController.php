<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\SendRegisterUserMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clm_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
            'mobile' => $data['mobile'],
        ]);
    }

    public function register(Request $request)
    {
        // dd($request);
        $this->validator($request->all())->validate();

        $this->create($request->all());

        $dataArray=[
            "subject"=>"Credentials for ".$request->name,
            "name"=> $request->name,
            "login"=>$request->email,
            "password"=>$request->password,
            "url"=>"https://clm.ict360.com/",
          ];
        
          $mailS = $this->EmailSend($dataArray);
        
        return redirect()->route('users-list')->with('status', 'User created successfully!');
    }

    public function EmailSend($dataArray)
    {
        $emailData = [
            "subject"=>$dataArray['subject'],
            "loginId"=>$dataArray['login'],
            "name"=>$dataArray['name'],
                        "password"=>$dataArray['password'],
                        "url"=>$dataArray['url'],
        ];
        // $result= Mail::to('pradeep.chahal@arkinfo.in')->cc('virendra.kumar@arkinfo.in')->send(new SendEmail($emailData));
        $result= Mail::to($dataArray['login'])->cc('virendra.kumar@arkinfo.in')->send(new SendRegisterUserMail($emailData));
        if($result!=null){
            ?>
            <script>
                alert("Something went wrong");
            </script>
            <?php
        }
    }
}
