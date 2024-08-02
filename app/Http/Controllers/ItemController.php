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

    public function search(Request $request)
    {

        $data['item'] = DB::table('firearms')
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
            ->select('firearms.*', 'types.name as type_name') // เลือกคอลัมน์ที่ต้องการ
            ->get();

        return response()->json($data);
    }

    // public function search(Request $request)
    // {
    //     $perPage = $request->input('perPage', 10); // Number of items per page
    //     $currentPage = $request->input('page', 1); // Current page

    //     $query = DB::table('firearms')
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
    //         ->select('firearms.*', 'types.name as type_name') // Select desired columns
    //         ->paginate($perPage, ['*'], 'page', $currentPage); // Add pagination

    //     return response()->json($query);
    // }

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
        $itemId = $request->input('itemId');
        $name = $request->input('name');
        $code = $request->input('code');
        $stock = $request->input('stock');
        $type = $request->input('type');

        DB::table('firearms')
            ->where('id', $itemId)
            ->update([
                'name' => $name,
                'code' => $code,
                'stock' => $stock,
                'type' => $type,
                'updated_at' => now(),
            ]);

            return response()->json(200);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'stock' => 'required|integer',
            'type' => 'required|integer',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('/image/item', 'public');
        // } else {
        //     return response()->json(['error' => 'No image file found.'], 400);
        // }
        
        DB::table('firearms')->insert([
            'name' => $request->name,
            'code' => $request->code,
            'stock' => $request->stock,
            'type' => $request->type,
            // 'images' => $imagePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
