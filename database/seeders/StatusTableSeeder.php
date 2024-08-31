<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item =[
            ['name' => 'แบบร่าง', 'created_at' => now()],
            ['name' => 'รอการอนุมัติ', 'created_at' => now()],
            ['name' => 'แก้ไข', 'created_at' => now()],
            ['name' => 'ไม่อนุมัติ', 'created_at' => now()],
            ['name' => 'ผ่านการอนุมัติ', 'created_at' => now()],
            ['name' => 'ส่งคืนเช็คสภาพ', 'created_at' => now()],
            ['name' => 'ส่งคืนสำเร็จ', 'created_at' => now()],
            ['name' => 'ถูกยกเลิก', 'created_at' => now()],
        ];
        DB::table('status')->insert($item);
    }
}
