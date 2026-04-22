<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.change-password');
    }

    public function checkCurrentPassword(Request $request)
{
    if (\Hash::check($request->current_password, auth()->user()->password)) {
        return response()->json(['valid' => true]);
    } else {
        return response()->json(['valid' => false]);
    }
}

    public function update(Request $request)
{
    $request->validate([
        'current_password' => ['required'],

        // ✅ new_password validation same as frontend
        'new_password' => [
            'required',
            'string',
            'min:8',
            'max:15',
            'confirmed', // ye confirm_password check karega
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/'
        ],
    ], [
        // Custom messages
        'new_password.regex' => 'Password must be 8-15 chars, include uppercase, lowercase, number, and special character.',
        'new_password.confirmed' => 'Confirm password does not match.',
    ]);

    // ✅ current password check
    if (!Hash::check($request->current_password, Auth::user()->password)) {
        return back()->with("error", "❌ Current password is incorrect!");
    }

    // ✅ update password
    Auth::user()->update([
        'password' => Hash::make($request->new_password),
    ]);

    // ✅ after success redirect with success message
    return back()->with("success", "✅ Password has been successfully changed.!");
}

}
