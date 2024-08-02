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
        // dd($request->all());

        // Validating the input
        // $request->validate([
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'username' => 'required|string|unique:users|max:255',
        //     'email' => 'required|email|unique:users|max:255',
        //     'sector' => 'required|exists:sectors,id',
        //     'password' => 'required|string|min:6|confirmed',
        // ]);

        // Insert the user into the database
        $user = DB::table('users')->insertGetId([
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'sector' => 1,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in
        Auth::loginUsingId($user);

        // Redirect to intended route
        return redirect()->intended('/dashboard');
    }
}
