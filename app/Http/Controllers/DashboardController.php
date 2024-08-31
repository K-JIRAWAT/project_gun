<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['status'] = DB::table('status')->get();

        $data['user'] = DB::table('users')
            ->where('status', null)
            ->get();

        return view('/admin/dashboard', $data);
    }

    public function user_search(Request $request)
    {
        $data['roles'] = DB::table('roles')->get();

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
            ->where('users.status', null)
            ->select('users.*', 'roles.name as role_name', 'sectors.name as sector_name') // เลือกคอลัมน์ที่ต้องการ
            ->get();

        return response()->json($data);
    }

    public function user_count(Request $request)
    {
        $data['count_user'] = DB::table('users')
            ->where('status', null)
            ->whereNull('deleted_at')
            ->count();

        $data['count_borrow_new'] = DB::table('list')
            ->where('status', 2)
            ->whereNull('deleted_at')
            ->count();

        $data['count_borrow_now'] = DB::table('list')
            ->where('status', 5)
            ->whereNull('deleted_at')
            ->count();

        return response()->json($data);
    }

    public function user_accept(Request $request)
    {
        DB::table('users')
            ->where('id', $request->user_id)
            ->update([
                'role_id' => $request->role,
                'status' => 1,
                'updated_at' => now(),
            ]);


        return response()->json(200);
    }

    public function user_reject(Request $request)
    {
        $currentUserId = Auth::id();

        DB::table('users')
            ->where('id', $request->user_id)
            ->update([
                'deleted_by' => $currentUserId,
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);


        return response()->json(200);
    }

    public function admin_list_search(Request $request)
    {
        $data['list'] = DB::table('list')
            ->join('status', 'list.status', '=', 'status.id')
            ->join('users', 'list.created_by', '=', 'users.id')
            ->when($request->status, function ($query, $status) {
                return $query->where('list.status', $status);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('list.request_no', 'like', "%{$search}%")
                    ->orWhere('users.firstname', 'like', "%{$search}%")
                    ->orWhere('users.lastname', 'like', "%{$search}%");
                });
            })
            ->when($request->month, function ($query, $month) {
                return $query->whereMonth('list.created_at', $month);
            })
            ->select('list.*', 'status.name as status_name', 'users.firstname as fname_borrow', 'users.lastname as lname_borrow')
            ->paginate($request->row_num);
            // ->paginate(1);
    
        return response()->json($data);
    }

    public function list_fix(Request $request)
    {
        $data_log = [
            'request_no' => $request->request_no,
            'status' => 3,
            'remarks' => $request->remarks,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => 3,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $request->request_no) // เงื่อนไขการอัปเดต
            ->update($data_list);


        return response()->json(200);
    }

    public function list_accept(Request $request)
    {
        $data_log = [
            'request_no' => $request->request_no,
            'status' => 5,
            // 'remarks' => $request->remarks,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => 5,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $request->request_no) // เงื่อนไขการอัปเดต
            ->update($data_list);


        return response()->json(200);
    }

    public function list_reject(Request $request)
    {
        $data_log = [
            'request_no' => $request->request_no,
            'status' => 4,
            'remarks' => $request->remarks,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => 4,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $request->request_no) // เงื่อนไขการอัปเดต
            ->update($data_list);


        return response()->json(200);
    }

    public function close(Request $request)
    {
        $data['item'] = DB::table('borrow')
            ->where('request_no', $request->request_no)
            ->get();

        foreach ($data['item'] as $item) {
            DB::table('firearms')
                ->where('id', $item->item_id)
                ->increment('stock', $item->borrow_num);
        }

        $data_log = [
            'request_no' => $request->request_no,
            'status' => 7,
            'remarks' => $request->remarks,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => 7,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $request->request_no) 
            ->update($data_list);




        return response()->json(200);
    }

}
