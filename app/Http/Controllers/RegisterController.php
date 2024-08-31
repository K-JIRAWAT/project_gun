<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $data['sector'] = DB::table('sectors')->get();

        return view('register', $data);
    }

    public function register(Request $request)
    {
        // ตรวจสอบ username และ email ว่ามีในระบบแล้วหรือยัง
        $existingUser = DB::table('users')
            ->where('username', $request->username)
            ->orWhere('email', $request->email)
            ->first();

        if ($existingUser) {
            // หากมี username หรือ email ซ้ำกัน ให้ทำการแจ้งเตือน
            return redirect()->back()->withInput($request->only('first_name', 'last_name', 'username', 'email', 'sector'))->with('error', 'ชื่อผู้ใช้งานหรืออีเมลนี้มีในระบบแล้ว กรุณาลองใหม่');
        }


        $user = DB::table('users')->insertGetId([
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'sector' => $request->sector,
            'status' => null,
            'email_verified_at' => now(),
            'updated_by' => 'system',
            'updated_at' => now(),
            'created_by' => 'system',
            'created_at' => now(),
            'password' => Hash::make($request->password),
        ]);

        session()->flash('status', 'success');
        session()->flash('message', 'คำร้องการสมัครถูกส่งแล้ว กรุณารอเจ้าหน้าที่อนุมัติ');

        return redirect('/login');
    }
}
