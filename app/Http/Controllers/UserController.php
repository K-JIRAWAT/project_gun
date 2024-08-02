<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data['roles'] = DB::table('roles')->get();

        return view('/admin/user', $data);
    }

    public function search(Request $request)
    {

        $data['user'] = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('sectors', 'users.sector', '=', 'sectors.id')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
                });
            })
            ->when($request->input('role'), function ($query, $role) {
                return $query->where('roles.id', $role);
            })
            ->whereNull('users.deleted_at')
            ->select('users.*', 'roles.name as role_name', 'sectors.name as sector_name') // เลือกคอลัมน์ที่ต้องการ
            ->get();

        return response()->json($data);
    }

    public function edit(Request $request)
    {
        $userId =  $request->user_id;

        $data['user'] = DB::table('users')
            ->whereNull('deleted_at')
            ->where('id', $userId)
            ->first();

        return response()->json($data);
    }

    public function save(Request $request)
    {
        $userId = $request->input('userId');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $username = $request->input('username');
        $email = $request->input('email');
        $role = $request->input('role');

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'email' => $email,
                'role_id' => $role,
                'updated_at' => now(),
            ]);

            return response()->json(200);
    }

    public function add(Request $request)
    {
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $username = $request->input('username');
        $email = $request->input('email');
        $role = $request->input('role');
        $password = Hash::make($request->input('password'));

        DB::table('users')
            ->insert([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'email' => $email,
                'role_id' => $role,
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(200);
    }

    public function delete(Request $request)
    {
        $userId =  $request->userId;

        DB::table('users')
        ->where('id', $userId)
        ->update([
            'deleted_at' => now(),
        ]);

        return response()->json(200);
    }
}
