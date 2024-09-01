<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $data['types'] = DB::table('types')->get();

        return view('/admin/item', $data);
    }

    // public function search(Request $request)
    // {

    //     $data['item'] = DB::table('firearms')
    //         ->leftJoin('types', 'firearms.type', '=', 'types.id')
    //         ->when($request->input('search'), function ($query, $search) {
    //             return $query->where(function($q) use ($search) {
    //                 $q->where('firearms.name', 'like', '%' . $search . '%')
    //                 ->orWhere('firearms.code', 'like', '%' . $search . '%');
    //             });
    //         })
    //         ->when($request->input('type'), function ($query, $type) {
    //             return $query->where('types.id', $type);
    //         })
    //         ->whereNull('firearms.deleted_at')
    //         ->select('firearms.*', 'types.name as type_name') // เลือกคอลัมน์ที่ต้องการ
    //         ->get();

    //     return response()->json($data);
    // }

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
            // ->get();

        $data['item'] = $query->paginate($request->input('row_num')); // Use paginate instead of get()

        // dd($query);

        return response()->json($data);
    }
    
    public function edit(Request $request)
    {
        $itemId =  $request->item_id;

        $data['item'] = DB::table('firearms')
            ->whereNull('deleted_at')
            ->where('id', $itemId)
            ->first();

        return response()->json($data);
    }

    public function save(Request $request)
    {

        if ($request->hasFile('image')) {
            // $imagePath = '/' . $request->file('image')->store('/image/item', 'public');
            $fileName = time() . '.' . $request->file('image')->getClientOriginalExtension();

            // กำหนดเส้นทางสำหรับการบันทึกไฟล์ใน app/public/image/item
            $destinationPath = public_path('image/item');

            // ย้ายไฟล์ไปยังเส้นทางที่กำหนด
            $request->file('image')->move($destinationPath, $fileName);

            // กำหนดเส้นทางของภาพที่บันทึกไว้ในฐานข้อมูล
            $imagePath = '/image/item/' . $fileName;

            DB::table('firearms')
                ->where('id', $request->itemId)
                ->update([
                    'name' => $request->name,
                    'stock' => $request->stock,
                    'type' => $request->type,
                    'images' => $imagePath,
                    'updated_at' => now(),
                ]);
        }else{
            DB::table('firearms')
            ->where('id', $request->itemId)
            ->update([
                'name' => $request->name,
                'stock' => $request->stock,
                'type' => $request->type,
                'updated_at' => now(),
            ]);
        }
        return response()->json(200);
    }

    public function addItem(Request $request)
    {
     

        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('/image/item', 'public');
        // } else {
        //     return response()->json(['error' => 'No image file found.'], 400);
        // }
        // dd($request->all());
        // $imagePath = null;
        if ($request->hasFile('image')) {
            // $imagePath = '/' . $request->file('image')->store('/image/item', 'public');
            $fileName = time() . '.' . $request->file('image')->getClientOriginalExtension();

            // กำหนดเส้นทางสำหรับการบันทึกไฟล์ใน app/public/image/item
            $destinationPath = public_path('image/item');

            // ย้ายไฟล์ไปยังเส้นทางที่กำหนด
            $request->file('image')->move($destinationPath, $fileName);

            // กำหนดเส้นทางของภาพที่บันทึกไว้ในฐานข้อมูล
            $imagePath = '/image/item/' . $fileName;
            DB::table('firearms')->insert([
                'name' => $request->name,
                'stock' => $request->stock,
                'type' => $request->type,
                'images' => $imagePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            DB::table('firearms')->insert([
                'name' => $request->name,
                'stock' => $request->stock,
                'type' => $request->type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $itemId =  $request->itemId;

        DB::table('firearms')
        ->where('id', $itemId)
        ->update([
            'deleted_at' => now(),
        ]);

        return response()->json(200);
    }
}
