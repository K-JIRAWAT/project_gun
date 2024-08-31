<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $data['types'] = DB::table('types')->get();

        return view('/user/home', $data);
    }

    public function search(Request $request)
    {

        $query = DB::table('firearms')
            ->leftJoin('types', 'firearms.type', '=', 'types.id')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('firearms.name', 'like', '%' . $search . '%')
                    ->orWhere('firearms.code', 'like', '%' . $search . '%');
                });
            })
            ->when($request->input('type'), function ($query, $type) {
                return $query->where('types.id', $type);
            })
            ->whereNull('firearms.deleted_at')
            ->select('firearms.*', 'types.name as type_name');

        $data['item'] = $query->paginate($request->input('row_num')); // Use paginate instead of get()

        return response()->json($data);
    }

    public function save(Request $request)
    {
        $items = $request->input('items', []);

        $latestBorrow = DB::table('list')
            ->whereDate('created_at', today()) // ตรวจสอบเฉพาะวันที่
            ->orderBy('created_at', 'desc')    // จัดเรียงตามวันที่สร้าง ล่าสุดไปก่อน
            ->first();

        $datePart = now()->format('Ymd'); // ใช้ 'Ymd' เพื่อให้เป็นรูปแบบ 'YYYYMMDD' (เฉพาะวันที่)
        $sequence = 1;
        
        if ($latestBorrow) {
            $lastRequestNo = $latestBorrow->request_no;
            
            // ดึงส่วนลำดับเลข 4 หลักสุดท้ายจากเลขที่คำขอล่าสุด
            $lastSequence = (int) substr($lastRequestNo, -4); 
            $sequence = $lastSequence + 1; // เพิ่มลำดับเลขอีก 1 จากเลขที่ล่าสุด
        }
        
        // สร้างเลขที่คำขอใหม่
        $requestNo = $datePart . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT); 
        
        foreach ($items as $item) {

            if($request->check == 2){

                $old_stock = DB::table('firearms')
                ->where('id', $item['id'])
                ->select('stock')
                ->first()->stock;

                $new_stock = $old_stock - $item['quantity'];

                DB::table('firearms')
                    ->where('id', $item['id'])
                    ->update(['stock' => $new_stock,
                        'updated_by' => $request->user_id,
                        'updated_at' => now()
                    ]);
            }
          
            $data = [
                'request_no' => $requestNo,
                'item_id' => $item['id'],
                'borrow_num' => $item['quantity'],
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
                'remark' => $request->remarks,
                'created_by' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ];
    
            DB::table('borrow')->insert($data);
        }

        if($request->check == 1){
            $status = 1;
        }else{
            $status = 2;
        }

        $data_log = [
            'request_no' => $requestNo,
            'status' => $status,
            'created_by' => $request->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'request_no' => $requestNo,
            'status' => $status,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'created_by' => $request->user_id,
            'remark' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('list')->insert($data_list);

        return response()->json($requestNo);
    }

    public function list_index()
    {
        // $data['list_borrow'] = DB::table('log_borrow')
        //     ->where('created_by', Auth::user()->id)
        //     ->orderBy('created_at', 'desc')
        //     ->first();

        $data['status'] = DB::table('status')->get();

        return view('/user/list', $data);
    }

    public function list_search(Request $request)
    {
        $data['list'] = DB::table('list')
            ->join('status', 'list.status', '=', 'status.id')
            ->where('list.created_by', Auth::user()->id)
            ->when($request->status, function ($query, $status) {
                return $query->where('list.status', $status);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('list.request_no', 'like', "%{$search}%");
                });
            })
            ->select('list.*', 'status.name as status_name')
            ->paginate($request->row_num);
            // ->paginate(1);
    
        return response()->json($data);
    }

    public function edit_index($request_no)
    {
        $userRoleId = Auth::user()->role_id;
        $data['layout'] = 'layout.user'; // Default layout
    
        if ($userRoleId == 1 || $userRoleId == 2) {
            $data['layout'] = 'layout.admin';
        }else{
            $data['layout'] = 'layout.user';
        }

        $data['types'] = DB::table('types')->get();

        $data['list'] = DB::table('list')
            ->join('status', 'list.status', '=', 'status.id')
            ->where('list.request_no', $request_no) 
            ->select('list.*', 'status.name as status_name') 
            ->first();

        // $data['selectedItems'] = DB::table('borrow')
        //     ->join('firearms', 'borrow.item_id', '=', 'firearms.id') // เข้าร่วมกับตาราง firearms
        //     ->where('borrow.request_no', $request_no) // กำหนดเงื่อนไข request_no
        //     ->select('borrow.*', 'firearms.images', 'firearms.stock', 'firearms.name as item_name') // เลือกข้อมูลที่ต้องการจากทั้งสองตาราง
        //     ->get();

        $data['selectedItems'] = DB::table('borrow')
            ->join('firearms', 'borrow.item_id', '=', 'firearms.id') // เข้าร่วมกับตาราง firearms
            ->join('types', 'firearms.type', '=', 'types.id') // เข้าร่วมกับตาราง type
            ->where('borrow.request_no', $request_no) // กำหนดเงื่อนไข request_no
            ->select('borrow.*', 'firearms.images', 'firearms.stock', 'firearms.name as item_name', 'types.name as type_name') // เลือกข้อมูลที่ต้องการจากทั้งสามตาราง
            ->get();

        // dd($data['selectedItems']);


        $data['user'] = DB::table('users')
            ->where('id', $data['list']->created_by)
            ->first();

        return view('/user/edit', $data);
    }

    
    public function edit_save(Request $request)
    {
        $items = $request->input('items', []);

        // สร้างเลขที่คำขอใหม่
        $requestNo = $request->request_no; 

        // dd($request->all());

        foreach ($items as $item) {

            if($request->check == 2){

                $old_stock = DB::table('firearms')
                ->where('id', $item['id'])
                ->select('stock')
                ->first()->stock;

                $new_stock = $old_stock - $item['quantity'];

                DB::table('firearms')
                    ->where('id', $item['id'])
                    ->update(['stock' => $new_stock,
                        'updated_by' => $request->user_id,
                        'updated_at' => now()
                    ]);
            }
          
            $data = [
                'request_no' => $requestNo,
                'item_id' => $item['id'],
                'borrow_num' => $item['quantity'],
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
                'remark' => $request->remarks,
                'created_by' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('borrow')
                ->updateOrInsert(
                    ['request_no' => $requestNo, 'item_id' => $item['id']], // เงื่อนไขการค้นหา
                    $data // ข้อมูลที่ต้องการอัปเดตหรือแทรก
                );
            $itemIdsInRequest[] = $item['id'];
        }

            DB::table('borrow')
                ->where('request_no', $requestNo)
                ->whereNotIn('item_id', $itemIdsInRequest)
                ->delete();
                
        if($request->check == 1){
            $status = 1;
        }else{
            $status = 2;
        }

        $data_log = [
            'request_no' => $requestNo,
            'status' => $status,
            'created_by' => $request->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => $status,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $requestNo) // เงื่อนไขการอัปเดต
            ->update($data_list);

        return response()->json($requestNo);
    }

    public function log_search(Request $request)
    {
        $data['log'] = DB::table('log_borrow')
            ->join('status', 'log_borrow.status', '=', 'status.id') // Join กับตาราง status
            ->join('users', 'log_borrow.created_by', '=', 'users.id') // Join กับตาราง users
            ->join('roles', 'users.role_id', '=', 'roles.id') // Join กับตาราง users
            ->where('log_borrow.request_no', $request->request_no)
            ->where('log_borrow.status', '!=', 1)
            ->select(
                'log_borrow.*', 
                'status.name as status_name', 
                'users.firstname as user_fname', 
                'roles.name as role_name', 
                'users.lastname as user_lname' // เลือกคอลัมน์ lastname จากตาราง users
            )
            ->orderBy('log_borrow.id', 'asc')
            ->get();
    
        // dd($data);
    
        return response()->json($data);
    }

    public function check(Request $request)
    {
        $data_log = [
            'request_no' => $request->request_no,
            'status' => 6,
            // 'remarks' => $request->remarks,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => 6,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $request->request_no) // เงื่อนไขการอัปเดต
            ->update($data_list);


        return response()->json(200);
    }

    public function cancel(Request $request)
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
            'status' => 8,
            // 'remarks' => $request->remarks,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('log_borrow')->insert($data_log);

        $data_list = [
            'status' => 8,
            'updated_at' => now(),
            'updated_by' => Auth::user()->id
        ];

        DB::table('list')
            ->where('request_no', $request->request_no) // เงื่อนไขการอัปเดต
            ->update($data_list);


        return response()->json(200);
    }
}
