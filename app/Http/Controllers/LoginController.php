<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('username', 'password');

    //     if (Auth::attempt($credentials) && Auth::user()->deleted_at === null) {
    //         return redirect()->intended('/admin/dashboard'); 
    //     }
    

    //     return redirect()->back()->withInput($request->only('username'))->with('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    // }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        if (Auth::attempt($credentials) && Auth::user()->deleted_at === null) {
            $user = Auth::user();
    
            if (in_array($user->role_id, [1, 2])) { 
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role_id == 3) { 
                return redirect()->intended('/user/home');
            }
    
            if($user->status === null){
                return redirect()->back()->withInput($request->only('username'))->with('error', 'บัญชีของท่านยังไม่ได้อนุมัติ');
            }
        }
    
        // หากไม่เจอ $user หรือไม่สามารถล็อกอินได้
        return redirect()->back()->withInput($request->only('username'))->with('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }
    
}
